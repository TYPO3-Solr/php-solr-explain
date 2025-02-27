<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Nodes;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Visitors\ExplainNodeVisitorInterface;
use ArrayObject;

/**
 * Represents a node of the explain result provided by solr.
 */
class Explain
{
    /**
     * Different types of nodes that need to be handled different during calculation
     */
    public const NODE_TYPE_SUM = 1;
    public const NODE_TYPE_MAX = 2;
    public const NODE_TYPE_PRODUCT = 4;
    public const NODE_TYPE_LEAF = 8;

    protected int $level = 0;

    protected string $content = '';

    protected float $score = 0.0;

    /**
     * @var ArrayObject<int, Explain>
     */
    protected ArrayObject $children;

    protected ?Explain $parent = null;

    protected int $nodeType = -1;

    protected string $fieldName = '*';

    public function __construct()
    {
        $this->children = new ArrayObject();
    }

    public function getAbsoluteImpactPercentage(): float|int|null
    {
        if ($this->level == 0) {
            return 100.0;
        }

        if ($this->getParent()?->getNodeType() == self::NODE_TYPE_SUM) {
            return $this->handleSumParent();
        }
        if ($this->getParent()?->getNodeType() == self::NODE_TYPE_MAX) {
            return $this->handleMaxParent();
        }
        if ($this->getParent()?->getNodeType() == self::NODE_TYPE_PRODUCT) {
            return $this->handleProductParent();
        }
        return null;
    }

    protected function handleProductParent(): float|null
    {
        $neighbors = $this->getParent()?->getChildren();

        if ($neighbors?->count() > 1) {
            $neighborScorePart = [];
            $parentPercentage = $this->getParent()?->getAbsoluteImpactPercentage();

            foreach ($neighbors as $neighbor) {
                if ($neighbor != $this) {
                    $neighborScore = $neighbor->getScore();
                    $neighborScorePart[] = $neighborScore;
                }
            }

            $scoreSum = array_sum($neighborScorePart) + $this->getScore();

            $multiplier = 100 / $scoreSum;
            $parentMultiplier = $parentPercentage / 100;
            return $this->getScore() * $multiplier * $parentMultiplier;
        }
        //when only one leaf in product is present we can inherit the parent score
        return $this->getParent()?->getAbsoluteImpactPercentage();
    }

    /**
     * @return float
     */
    protected function handleSumParent(): float
    {
        $parentScore = $this->getParent()?->getScore();
        $parentPercentage = $this->getParent()?->getAbsoluteImpactPercentage();

        //part of this node relative to the parent
        $scorePercentageToParent = (100 / $parentScore) * $this->getScore();
        return ($parentPercentage / 100) * $scorePercentageToParent;
    }

    protected function handleMaxParent(): float|null
    {
        $neighbors = $this->getParent()?->getChildren();
        $tieBreaker = $this->getParent()?->getTieBreaker() ?? 0.0;

        $parentScore = $this->getParent()?->getScore();
        $parentScorePart = ($this->getScore() / $parentScore) * (100);
        $parentScorePartPercentage = $this->getParent()?->getAbsoluteImpactPercentage() / 100.0;

        $isMaxNode = true;
        foreach ($neighbors ?? [] as $neighbor) {
            if ($neighbor != $this && $neighbor->getScore() > $this->getScore()) {
                $isMaxNode = false;
                break;
            }
        }

        if ($tieBreaker > 0) {
            if ($isMaxNode) {
                return $parentScorePart * $parentScorePartPercentage;
            }
            return $parentScorePart * $parentScorePartPercentage * $tieBreaker;
        }
        if ($isMaxNode) {
            return $this->getParent()?->getAbsoluteImpactPercentage();
        }
        return 0.0;
    }

    /**
     * @param ?Explain $parent
     */
    public function setParent(?Explain $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): ?Explain
    {
        return $this->parent;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * @param ArrayObject<int, Explain> $children
     * @noinspection PhpUnused
     */
    public function setChildren(ArrayObject $children): void
    {
        $this->children = $children;
    }

    /**
     * @return ?ArrayObject<int, Explain>
     */
    public function getChildren(): ?ArrayObject
    {
        return $this->children;
    }

    public function getChild(int $index): ?Explain
    {
        return $this->children[$index] ?? null;
    }

    /**
     * @noinspection PhpUnused
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param float $score
     */
    public function setScore(float $score): void
    {
        $this->score = $score;
    }

    /**
     * @return float
     */
    public function getScore(): float
    {
        return $this->score;
    }

    /**
     * @param int $nodeType
     */
    protected function setNodeType(int $nodeType): void
    {
        $this->nodeType = $nodeType;
    }

    /**
     * @return int
     */
    public function getNodeType(): int
    {
        return $this->nodeType;
    }

    /**
     * @param string $fieldName
     */
    public function setFieldName(string $fieldName): void
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @noinspection PhpUnused
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getTieBreaker(): float
    {
        $matches = [];
        preg_match('~plus (?<tiebreaker>.*) times~', $this->getContent(), $matches);

        return (float)($matches['tiebreaker'] ?? 0.0);
    }

    /**
     * This method can be used to traverse all child nodes.
     *
     * @param ExplainNodeVisitorInterface $visitor
     */
    public function visitNodes(ExplainNodeVisitorInterface $visitor): void
    {
        $visitor->visit($this);
        foreach ($this->getChildren() ?? [] as $child) {
            $child->visitNodes($visitor);
        }
    }
}

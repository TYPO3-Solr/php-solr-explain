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

    /**
     * @var int
     */
    protected $level = 0;

    /**
     * @var string
     */
    protected $content = '';

    /**
     * @var float
     */
    protected $score = 0.0;

    /**
     * @var ArrayObject
     */
    protected $children;

    /**
     * @var Explain
     */
    protected $parent;

    /**
     * @var int
     */
    protected $nodeType = -1;

    /**
     * @var string
     */
    protected $fieldName = '*';

    public function __construct()
    {
        $this->children = new ArrayObject();
    }

    /**
     * @return ?float|?int
     */
    public function getAbsoluteImpactPercentage()
    {
        if ($this->level == 0) {
            return 100.0;
        }

        if ($this->getParent()->getNodeType() == self::NODE_TYPE_SUM) {
            return $this->handleSumParent();
        }
        if ($this->getParent()->getNodeType() == self::NODE_TYPE_MAX) {
            return $this->handleMaxParent();
        }
        if ($this->getParent()->getNodeType() == self::NODE_TYPE_PRODUCT) {
            return $this->handleProductParent();
        }
        return null;
    }

    /**
     * @return float
     */
    protected function handleProductParent()
    {
        $neighbors = $this->getParent()->getChildren();

        if ($neighbors->count() > 1) {
            $neighborScorePart = [];
            $parentPercentage = $this->getParent()->getAbsoluteImpactPercentage();

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
        return $this->getParent()->getAbsoluteImpactPercentage();
    }

    /**
     * @return float
     */
    protected function handleSumParent()
    {
        $parentScore = $this->getParent()->getScore();
        $parentPercentage = $this->getParent()->getAbsoluteImpactPercentage();

        //part of this node relative to the parent
        $scorePercentageToParent = (100 / $parentScore) * $this->getScore();
        return ($parentPercentage / 100) * $scorePercentageToParent;
    }

    /**
     * @return float
     */
    protected function handleMaxParent()
    {
        $neighbors = $this->getParent()->getChildren();
        $tieBreaker = $this->getParent()->getTieBreaker();

        $parentScore = $this->getParent()->getScore();
        $parentScorePart = ($this->getScore() / $parentScore) * (100);
        $parentScorePartPercentage = $this->getParent()->getAbsoluteImpactPercentage() / 100.0;

        $isMaxNode = true;
        foreach ($neighbors as $neighbor) {
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
            return $this->getParent()->getAbsoluteImpactPercentage();
        }
        return 0.0;
    }

    /**
     * @param ?Explain $parent
     */
    public function setParent(?Explain $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Explain
     */
    public function getParent(): ?Explain
    {
        return $this->parent;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content)
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
    public function setLevel(int $level)
    {
        $this->level = $level;
    }

    /**
     * @param ArrayObject $children
     */
    public function setChildren(ArrayObject $children)
    {
        $this->children = $children;
    }

    /**
     * @return ArrayObject
     */
    public function getChildren(): ?ArrayObject
    {
        return $this->children;
    }

    /**
     * @param $index
     * @return Explain
     */
    public function getChild($index): Explain
    {
        return $this->children[$index];
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param float $score
     */
    public function setScore(float $score)
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
    protected function setNodeType(int $nodeType)
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
    public function setFieldName(string $fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * This method can be used to traverse all child nodes.
     *
     * @param ExplainNodeVisitorInterface $visitor
     */
    public function visitNodes(ExplainNodeVisitorInterface $visitor)
    {
        $visitor->visit($this);
        foreach ($this->getChildren() as $child) {
            $child->visitNodes($visitor);
        }
    }
}

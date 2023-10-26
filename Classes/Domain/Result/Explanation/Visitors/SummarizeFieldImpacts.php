<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Visitors;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Nodes\Explain;

/**
 * This visitor is used to determine a percentage impacts
 * by a fieldname.
 */
class SummarizeFieldImpacts implements ExplainNodeVisitorInterface
{
    /**
     * @var float[]
     */
    protected $sums = [];

    /**
     * @param Explain $node
     */
    public function visit(Explain $node)
    {
        if ($node->getNodeType() == $node::NODE_TYPE_LEAF) {
            if ($node->getParent() != null) {
                $fieldName = $this->getClosestFieldName($node);
                if (trim($fieldName) === '') {
                    return;
                }

                if (!isset($this->sums[$fieldName])) {
                    $this->sums[$fieldName] = 0;
                }

                $this->sums[$fieldName] += $node->getAbsoluteImpactPercentage();
            }
        }
    }

    /**
     * Returns the closest fieldname in the parent root line and and empty string when none is present.
     *
     * @param $node
     * @return string
     */
    protected function getClosestFieldName($node): string
    {
        while (!is_null($node->getParent())) {
            $parent = $node->getParent();
            if ($parent->getFieldName() !== '') {
                return $parent->getFieldName();
            }

            $node = $parent;
        }

        return '';
    }

    /**
     * Returns the fieldnames that have been relevant during the explain.
     *
     * @return string[]
     */
    public function getRelevantFieldNames(): array
    {
        return array_keys($this->sums);
    }

    /**
     * Returns the impact for a certain field by name.
     *
     * @param $fieldName
     * @return float
     */
    public function getFieldImpact($fieldName): float
    {
        if (!array_key_exists($fieldName, $this->sums)) {
            return 0.0;
        }

        return $this->sums[$fieldName];
    }

    /**
     * Returns the fieldname => impact array.
     *
     * @return float[]
     */
    public function getFieldImpacts(): array
    {
        return $this->sums;
    }
}

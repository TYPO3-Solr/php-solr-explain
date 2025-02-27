<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Visitors;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Nodes\Explain;

/**
 * This visitor is used to summarize the impacts of all leaf
 * nodes. For a normal explain this should be 100%, and it is used
 * for verification.
 */
class SummarizeLeafImpacts implements ExplainNodeVisitorInterface
{
    protected float $sum = 0.0;

    /**
     * @param Explain $node
     */
    public function visit(Explain $node): void
    {
        if ($node->getNodeType() == $node::NODE_TYPE_LEAF) {
            $this->sum += $node->getAbsoluteImpactPercentage();
        }
    }

    public function getSum(): float
    {
        return round($this->sum, 1);
    }
}

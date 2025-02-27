<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Visitors;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Nodes\Explain;

/**
 * Visitor interface, needs to be implemented by a node visitor.
 */
interface ExplainNodeVisitorInterface
{
    public function visit(Explain $node): void;
}

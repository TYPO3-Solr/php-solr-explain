<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Nodes;

class Sum extends Explain
{
    public function __construct()
    {
        parent::__construct();
        $this->setNodeType(self::NODE_TYPE_SUM);
    }
}

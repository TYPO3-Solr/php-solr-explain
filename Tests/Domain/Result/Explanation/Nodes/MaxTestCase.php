<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Explanation\Nodes;

use ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Explanation\AbstractExplanationTestCase;

class MaxTestCase extends AbstractExplanationTestCase
{
    public function testGetTieBreaker()
    {
        $maxNode = new \ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Nodes\Max();
        $maxNode->setContent('8.040816E-4 = (MATCH) max plus 0.7 times others of:');
        self::assertEquals(0.7, $maxNode->getTieBreaker());
    }
}

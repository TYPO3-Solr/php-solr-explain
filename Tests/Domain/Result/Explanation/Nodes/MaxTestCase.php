<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Explanation\Nodes;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Nodes\Max;
use ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Explanation\AbstractExplanationTest;

class MaxTestCase extends AbstractExplanationTest
{
    public function testGetTieBreaker(): void
    {
        $maxNode = new Max();
        $maxNode->setContent('8.040816E-4 = (MATCH) max plus 0.7 times others of:');
        self::assertEquals(0.7, $maxNode->getTieBreaker());
    }
}

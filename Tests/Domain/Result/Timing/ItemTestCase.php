<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Timing;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing\Item;
use ApacheSolrForTypo3\SolrExplain\Tests\AbstractSolrTest;

class ItemTestCase extends AbstractSolrTest
{
    /**
     * @test
     */
    public function testSetTimeSpend()
    {
        $timingItem = new Item();
        self::assertEquals(0.0, $timingItem->getTimeSpend());
        $timingItem->setTimeSpend(1.0);
        self::assertEquals(1.0, $timingItem->getTimeSpend());
    }
}

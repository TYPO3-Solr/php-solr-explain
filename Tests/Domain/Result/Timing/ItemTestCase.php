<?php

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing\Item;
use ApacheSolrForTypo3\SolrExplain\Tests\AbstractSolrTest;

class ItemTestCase extends AbstractSolrTest
{
    /**
     * @var Item
     */
    protected $timingItem;

    protected function setUp(): void
    {
        $this->timingItem = new Item();
    }

    /**
     * @test
     */
    public function testSetTimeSpend()
    {
        self::assertEquals(0.0, $this->timingItem->getTimeSpend());
        $this->timingItem->setTimeSpend(1.0);
        self::assertEquals(1.0, $this->timingItem->getTimeSpend());
    }
}

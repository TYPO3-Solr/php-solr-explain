<?php

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing\Item;
use ApacheSolrForTypo3\SolrExplain\Tests\AbstractSolrTest;

class ItemTestCase extends AbstractSolrTest
{

	/**
	 * @var Item
	 */
	protected $timingItem;

	/**
	 * @return void
	 */
    protected function setUp(): void
    {
		$this->timingItem = new Item();
	}

	/**
	 * @test
	 */
	public function testSetTimeSpend()
    {
		$this->assertEquals(0.0, $this->timingItem->getTimeSpend());
		$this->timingItem->setTimeSpend(1.0);
		$this->assertEquals(1.0, $this->timingItem->getTimeSpend());
	}
}
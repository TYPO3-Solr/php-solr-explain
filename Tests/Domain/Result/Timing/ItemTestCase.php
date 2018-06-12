<?php

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing\Item;

class ItemTestCase extends \ApacheSolrForTypo3\SolrExplain\Tests\AbstractSolrTest {

	/**
	 * @var Item
	 */
	protected $timingItem;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->timingItem = new Item();
	}

	/**
	 * @test
	 */
	public function testSetTimeSpend() {
		$this->assertEquals(0.0, $this->timingItem->getTimeSpend());
		$this->timingItem->setTimeSpend(1.0);
		$this->assertEquals(1.0, $this->timingItem->getTimeSpend());
	}
}
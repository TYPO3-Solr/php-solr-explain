<?php

/**
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
 */
class ItemTestCase extends \Solr\Tests\AbstractSolrTest {

	/**
	 * @var \Solr\Domain\Result\Timing\Item
	 */
	protected $timingItem;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->timingItem = new \Solr\Domain\Result\Timing\Item();
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
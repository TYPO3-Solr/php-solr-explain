<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing;

/**
 * Collection of timing items used to group timing items
 * by processing or preparation state.
 */
class ItemCollection extends \ArrayObject {

	/**
	 * @var float
	 */
	protected $timeSpend = 0.0;

	/**
	 * @param float $timeSpend
	 * @return ItemCollection
	 */
	public function setTimeSpend($timeSpend) {
		$this->timeSpend = $timeSpend;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getTimeSpend() {
		return $this->timeSpend;
	}
}
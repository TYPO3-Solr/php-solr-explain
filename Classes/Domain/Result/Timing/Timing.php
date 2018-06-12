<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing\ItemCollection;

/**
 * Timing information of a solr response.
 */
class Timing {

	/**
	 * @var ItemCollection
	 */
	protected $preparationItems;

	/**
	 * @var ItemCollection
	 */
	protected $processingItems;

	/**
	 * @var float
	 */
	protected $timeSpend = 0.0;

	/**
	 * @param ItemCollection $preparationItems
	 * @param ItemCollection $processingItems
	 */
	public function __construct(ItemCollection $preparationItems, ItemCollection $processingItems) {
		$this->preparationItems = $preparationItems;
		$this->processingItems = $processingItems;
	}

	/**
	 * @param float $timeSpend
	 */
	public function setTimeSpend($timeSpend) {
		$this->timeSpend = $timeSpend;
	}

	/**
	 * @return float
	 */
	public function getTimeSpend() {
		return $this->timeSpend;
	}

	/**
	 * @param ItemCollection $processingItems
	 */
	public function setProcessingItems($processingItems) {
		$this->processingItems = $processingItems;
	}

	/**
	 * @return ItemCollection
	 */
	public function getProcessingItems() {
		return $this->processingItems;
	}

	/**
	 * @param ItemCollection $preparationItems
	 */
	public function setPreparationItems($preparationItems) {
		$this->preparationItems = $preparationItems;
	}

	/**
	 * @return ItemCollection
	 */
	public function getPreparationItems() {
		return $this->preparationItems;
	}
}

?>
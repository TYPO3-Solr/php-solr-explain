<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing;

/**
 * Timing information of a solr response.
 */
class Timing
{

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
	public function __construct(ItemCollection $preparationItems, ItemCollection $processingItems)
    {
		$this->preparationItems = $preparationItems;
		$this->processingItems = $processingItems;
	}

	/**
	 * @param float $timeSpend
	 */
	public function setTimeSpend(float $timeSpend)
    {
		$this->timeSpend = $timeSpend;
	}

	/**
	 * @return float
	 */
	public function getTimeSpend(): float
    {
		return $this->timeSpend;
	}

	/**
	 * @param ItemCollection $processingItems
	 */
	public function setProcessingItems(ItemCollection $processingItems)
    {
		$this->processingItems = $processingItems;
	}

	/**
	 * @return ItemCollection
	 */
	public function getProcessingItems(): ItemCollection
    {
		return $this->processingItems;
	}

	/**
	 * @param ItemCollection $preparationItems
	 */
	public function setPreparationItems(ItemCollection $preparationItems)
    {
		$this->preparationItems = $preparationItems;
	}

	/**
	 * @return ItemCollection
	 */
	public function getPreparationItems(): ItemCollection
    {
		return $this->preparationItems;
	}
}

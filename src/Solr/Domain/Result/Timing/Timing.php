<?php

namespace Solr\Domain\Result\Timing;

/**
 * Timing information of a solr response.
 *
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
 */
class Timing {

	/**
	 * @var \Solr\Domain\Result\Timing\ItemCollection
	 */
	protected $preparationItems;

	/**
	 * @var \Solr\Domain\Result\Timing\ItemCollection
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
	public function __construct(
		\Solr\Domain\Result\Timing\ItemCollection $preparationItems,
		\Solr\Domain\Result\Timing\ItemCollection $processingItems
	) {
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
	 * @param \Solr\Domain\Result\Timing\ItemCollection $processingItems
	 */
	public function setProcessingItems($processingItems) {
		$this->processingItems = $processingItems;
	}

	/**
	 * @return \Solr\Domain\Result\Timing\ItemCollection
	 */
	public function getProcessingItems() {
		return $this->processingItems;
	}

	/**
	 * @param \Solr\Domain\Result\Timing\ItemCollection $preparationItems
	 */
	public function setPreparationItems($preparationItems) {
		$this->preparationItems = $preparationItems;
	}

	/**
	 * @return \Solr\Domain\Result\Timing\ItemCollection
	 */
	public function getPreparationItems() {
		return $this->preparationItems;
	}
}

?>
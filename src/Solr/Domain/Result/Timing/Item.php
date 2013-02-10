<?php

namespace Solr\Domain\Result\Timing;

class Item {

	/**
	 * @var float
	 */
	protected $timeSpend = 0.0;

	/**
	 * @var string
	 */
	protected $componentName = '';

	/**
	 * @param string $componentName
	 * @return \Solr\Domain\Result\Timing\Item
	 */
	public function setComponentName($componentName) {
		$this->componentName = $componentName;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getComponentName() {
		return $this->componentName;
	}

	/**
	 * @param float $timeSpend
	 * @return \Solr\Domain\Result\Timing\Item
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

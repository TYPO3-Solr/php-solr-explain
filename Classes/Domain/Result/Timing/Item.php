<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing;

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
	 * @return self
	 */
	public function setComponentName(string $componentName): Item
    {
		$this->componentName = $componentName;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getComponentName(): string
    {
		return $this->componentName;
	}

	/**
	 * @param float $timeSpend
	 * @return self
	 */
	public function setTimeSpend(float $timeSpend): Item
    {
		$this->timeSpend = $timeSpend;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getTimeSpend(): float
    {
		return $this->timeSpend;
	}
}

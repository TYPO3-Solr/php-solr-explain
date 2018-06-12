<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Document\Collection;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing\Timing;

class Result {

	/**
	 * @var integer
	 */
	protected $completeResultCount = 0;

	/**
	 * @var integer
	 */
	protected $queryTime = 0;

	/**
	 * @var string
	 */
	protected $queryParser = '';

	/**
	 * @var Collection
	 */
	protected $documentCollection;

	/**
	 * @var Timing
	 */
	protected $timing = null;

	/**
	 * @param int $completeResultCount
	 * @return \ApacheSolrForTypo3\SolrExplain\Domain\Result\Result
	 */
	public function setCompleteResultCount($completeResultCount) {
		$this->completeResultCount = $completeResultCount;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getCompleteResultCount() {
		return $this->completeResultCount;
	}

	/**
	 * @return int
	 */
	public function getResultCount() {
		return $this->documentCollection->count();
	}

	/**
	 * @param int $queryTime
	 * @return \ApacheSolrForTypo3\SolrExplain\Domain\Result\Result
	 */
	public function setQueryTime($queryTime) {
		$this->queryTime = $queryTime;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getQueryTime() {
		return $this->queryTime;
	}

	/**
	 * @param Collection $documentCollection
	 */
	public function setDocumentCollection(Collection $documentCollection) {
		$this->documentCollection = $documentCollection;
	}

	/**
	 * @return Collection
	 */
	public function getDocumentCollection() {
		return $this->documentCollection;
	}

	/**
	 * @param Timing $timing
	 */
	public function setTiming(Timing $timing) {
		$this->timing = $timing;
	}

	/**
	 * @return Timing
	 */
	public function getTiming() {
		return $this->timing;
	}

	/**
	 * @param string $queryParser
	 */
	public function setQueryParser($queryParser) {
		$this->queryParser = $queryParser;
	}

	/**
	 * @return string
	 */
	public function getQueryParser() {
		return $this->queryParser;
	}
}
<?php

namespace Solr\Domain\Result;

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
	 * @var \Solr\Domain\Result\Document\Collection
	 */
	protected $documentCollection;

	/**
	 * @param int $completeResultCount
	 * @return \Solr\Domain\Result\Result
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
	 * @return \Solr\Domain\Result\Result
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
	 * @param \Solr\Domain\Result\Document\Collection $documentCollection
	 */
	public function setDocumentCollection(\Solr\Domain\Result\Document\Collection $documentCollection) {
		$this->documentCollection = $documentCollection;
	}

	/**
	 * @return \Solr\Domain\Result\Document\Collection
	 */
	public function getDocumentCollection() {
		return $this->documentCollection;
	}
}
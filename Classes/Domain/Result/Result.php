<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Document\Collection;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing\Timing;

class Result
{
    /**
     * @var int
     */
    protected $completeResultCount = 0;

    /**
     * @var int
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
    protected $timing;

    /**
     * @param int $completeResultCount
     * @return self
     */
    public function setCompleteResultCount(int $completeResultCount): Result
    {
        $this->completeResultCount = $completeResultCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getCompleteResultCount(): int
    {
        return $this->completeResultCount;
    }

    /**
     * @return int
     */
    public function getResultCount(): int
    {
        return $this->documentCollection->count();
    }

    /**
     * @param int $queryTime
     * @return self
     */
    public function setQueryTime(int $queryTime): Result
    {
        $this->queryTime = $queryTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getQueryTime(): int
    {
        return $this->queryTime;
    }

    /**
     * @param Collection $documentCollection
     */
    public function setDocumentCollection(Collection $documentCollection)
    {
        $this->documentCollection = $documentCollection;
    }

    /**
     * @return Collection
     */
    public function getDocumentCollection(): Collection
    {
        return $this->documentCollection;
    }

    /**
     * @param Timing $timing
     */
    public function setTiming(Timing $timing)
    {
        $this->timing = $timing;
    }

    /**
     * @return Timing
     */
    public function getTiming(): ?Timing
    {
        return $this->timing;
    }

    /**
     * @param string $queryParser
     */
    public function setQueryParser(string $queryParser)
    {
        $this->queryParser = $queryParser;
    }

    /**
     * @return string
     */
    public function getQueryParser(): string
    {
        return $this->queryParser;
    }
}

<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Document\Collection;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing\Timing;

class Result
{
    protected int $completeResultCount = 0;

    protected int $queryTime = 0;

    protected string $queryParser = '';

    protected ?Collection $documentCollection = null;

    protected ?Timing $timing = null;

    public function setCompleteResultCount(int $completeResultCount): Result
    {
        $this->completeResultCount = $completeResultCount;
        return $this;
    }

    public function getCompleteResultCount(): int
    {
        return $this->completeResultCount;
    }

    public function getResultCount(): int
    {
        return $this->documentCollection->count();
    }

    public function setQueryTime(int $queryTime): Result
    {
        $this->queryTime = $queryTime;
        return $this;
    }

    public function getQueryTime(): int
    {
        return $this->queryTime;
    }

    public function setDocumentCollection(Collection $documentCollection): Result
    {
        $this->documentCollection = $documentCollection;
        return $this;
    }

    public function getDocumentCollection(): Collection
    {
        return $this->documentCollection;
    }

    public function setTiming(Timing $timing): Result
    {
        $this->timing = $timing;
        return $this;
    }

    public function getTiming(): ?Timing
    {
        return $this->timing;
    }

    public function setQueryParser(string $queryParser): Result
    {
        $this->queryParser = $queryParser;
        return $this;
    }

    /**
     * @return string
     */
    public function getQueryParser(): string
    {
        return $this->queryParser;
    }
}

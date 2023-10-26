<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing;

/**
 * Timing information of a solr response.
 */
class Timing
{
    protected ItemCollection $preparationItems;

    protected ItemCollection $processingItems;

    protected float $timeSpend = 0.0;

    public function __construct(ItemCollection $preparationItems, ItemCollection $processingItems)
    {
        $this->preparationItems = $preparationItems;
        $this->processingItems = $processingItems;
    }

    public function setTimeSpend(float $timeSpend): Timing
    {
        $this->timeSpend = $timeSpend;
        return $this;
    }

    public function getTimeSpend(): float
    {
        return $this->timeSpend;
    }

    public function setProcessingItems(ItemCollection $processingItems): Timing
    {
        $this->processingItems = $processingItems;
        return $this;
    }

    public function getProcessingItems(): ItemCollection
    {
        return $this->processingItems;
    }

    public function setPreparationItems(ItemCollection $preparationItems): Timing
    {
        $this->preparationItems = $preparationItems;
        return $this;
    }

    public function getPreparationItems(): ItemCollection
    {
        return $this->preparationItems;
    }
}

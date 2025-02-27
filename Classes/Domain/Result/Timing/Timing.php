<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing;

/**
 * Timing information of a solr response.
 */
class Timing
{
    protected float $timeSpend = 0.0;

    public function __construct(
        protected ItemCollection $preparationItems,
        protected ItemCollection $processingItems,
    ) {}

    public function setTimeSpend(float $timeSpend): Timing
    {
        $this->timeSpend = $timeSpend;
        return $this;
    }

    public function getTimeSpend(): float
    {
        return $this->timeSpend;
    }

    /**
     * @param ItemCollection<int, Item> $processingItems
     *
     * @noinspection PhpUnused
     */
    public function setProcessingItems(ItemCollection $processingItems): Timing
    {
        $this->processingItems = $processingItems;
        return $this;
    }

    /**
     * @return ItemCollection<int, Item> $preparationItems
     */
    public function getProcessingItems(): ItemCollection
    {
        return $this->processingItems;
    }

    /**
     * @noinspection PhpUnused
     * @param ItemCollection<int, Item> $preparationItems
     */
    public function setPreparationItems(ItemCollection $preparationItems): Timing
    {
        $this->preparationItems = $preparationItems;
        return $this;
    }

    /**
     * @noinspection PhpUnused
     *
     * @return ItemCollection<int, Item>
     */
    public function getPreparationItems(): ItemCollection
    {
        return $this->preparationItems;
    }
}

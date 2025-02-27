<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing;

use ArrayObject;

/**
 * Collection of timing items used to group timing items
 * by processing or preparation state.
 *
 * @template-extends ArrayObject<int, Item>
 */
class ItemCollection extends ArrayObject
{
    protected float $timeSpend = 0.0;

    /**
     * @return $this
     */
    public function setTimeSpend(float $timeSpend): ItemCollection
    {
        $this->timeSpend = $timeSpend;
        return $this;
    }

    /**
     * @noinspection PhpUnused
     */
    public function getTimeSpend(): float
    {
        return $this->timeSpend;
    }
}

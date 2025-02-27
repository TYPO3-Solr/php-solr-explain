<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing;

class Item
{
    protected float $timeSpend = 0.0;

    protected string $componentName = '';

    public function setComponentName(string $componentName): Item
    {
        $this->componentName = $componentName;

        return $this;
    }

    /**
     * @noinspection PhpUnused
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

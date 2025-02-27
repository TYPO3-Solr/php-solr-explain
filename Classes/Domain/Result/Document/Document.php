<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Document;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Document\Field\Collection;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Document\Field\Field;

class Document
{
    protected Collection $fieldCollection;

    protected string $rawExplainData;

    public function __construct()
    {
        $this->fieldCollection = new Collection();
    }

    public function addField(Field $field): void
    {
        $this->fieldCollection->offsetSet($field->getName(), $field);
    }

    /**
     * @noinspection PhpUnused
     */
    public function getFields(): Collection
    {
        return $this->fieldCollection;
    }

    public function getFieldByName(string $fieldName): ?Field
    {
        return $this->fieldCollection->offsetGet($fieldName);
    }

    /**
     * @param string $rawExplainData
     */
    public function setRawExplainData(string $rawExplainData): void
    {
        $this->rawExplainData = $rawExplainData;
    }

    /**
     * @return string
     */
    public function getRawExplainData(): string
    {
        return $this->rawExplainData;
    }
}

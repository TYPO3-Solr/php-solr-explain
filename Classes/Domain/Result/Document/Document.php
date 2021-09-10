<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Document;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Document\Field\Collection;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Document\Field\Field;

class Document
{

	/**
	 * @var Collection
	 */
	protected $fieldCollection;

	/**
	 * @var string
	 */
	protected $rawExplainData;

	/**
	 * @return void
	 */
	public function __construct()
    {
		$this->fieldCollection = new Collection();
	}

	/**
	 * @param Field $field
	 */
	public function addField(Field $field)
    {
		$this->fieldCollection->offsetSet($field->getName(),$field);
	}

	/**
	 * @return Collection
	 */
	public function getFields(): Collection
    {
		return $this->fieldCollection;
	}

	/**
	 * @param string
	 * @return Field
	 */
	public function getFieldByName(string $fieldName): Field
    {
		return $this->fieldCollection->offsetGet($fieldName);
	}

	/**
	 * @param string $rawExplainData
	 */
	public function setRawExplainData(string $rawExplainData)
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

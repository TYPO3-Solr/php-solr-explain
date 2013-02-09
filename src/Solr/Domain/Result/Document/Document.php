<?php

namespace Solr\Domain\Result\Document;

class Document {

	/**
	 * @var \Solr\Domain\Result\Document\Field\Collection
	 */
	protected $fieldCollection;

	/**
	 * @var string
	 */
	protected $rawExplainData;

	/**
	 * @return void
	 */
	public function __construct() {
		$this->fieldCollection = new \Solr\Domain\Result\Document\Field\Collection();
	}

	/**
	 * @param Field\Field $field
	 */
	public function addField(\Solr\Domain\Result\Document\Field\Field $field) {
		$this->fieldCollection->offsetSet($field->getName(),$field);
	}

	/**
	 * @return Field\Collection
	 */
	public function getFields() {
		return $this->fieldCollection;
	}

	/**
	 * @param string
	 * @return Field\Field
	 */
	public function getFieldByName($fieldName) {
		return $this->fieldCollection->offsetGet($fieldName);
	}

	/**
	 * @param string $rawExplainData
	 */
	public function setRawExplainData($rawExplainData) {
		$this->rawExplainData = $rawExplainData;
	}

	/**
	 * @return string
	 */
	public function getRawExplainData() {
		return $this->rawExplainData;
	}
}

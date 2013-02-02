<?php

namespace SolrExplain\Domain\Explanation;

class Explain {

	/**
	 * Id of the relevant document.
	 *
	 * @var string
	 */
	protected $documentId = '';

	/**
	 * @var \ArrayObject
	 */
	protected $children = null;

	/**
	 * @var \SolrExplain\Domain\Explanation\ExplainNode
	 */
	protected $rootNode = null;

	/**
	 * @var null
	 */
	protected $attributes = null;

	/**
	 * @return void
	 */
	public function __construct() {
		$this->attributes = new \ArrayObject();
	}

	/**
	 * @param \ArrayObject $children
	 */
	public function setChildren($children) {
		$this->children = $children;
	}

	/**
	 * @return \ArrayObject<\SolrExplain\Domain\Explanation\ExplainNode>
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * @param $index
	 */
	public function getChild($index) {
		return $this->children[$index];
	}

	/**
	 * @param \SolrExplain\Domain\Explanation\ExplainNode $rootNode
	 */
	public function setRootNode($rootNode) {
		$this->rootNode = $rootNode;
	}

	/**
	 * @return \SolrExplain\Domain\Explanation\ExplainNode
	 */
	public function getRootNode() {
		return $this->rootNode;
	}

	/**
	 * @param string $documentId
	 */
	public function setDocumentId($documentId) {
		$this->documentId = $documentId;
	}

	/**
	 * @return string
	 */
	public function getDocumentId() {
		return $this->documentId;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function setAttribute($key, $value) {
		$this->attributes->offsetSet($key,$value);
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function getAttribute($key) {
		return $this->attributes->offsetGet($key);
	}
}
<?php

namespace SolrExplain\Domain\Explanation;

/**
 * Root object of the parse explain.
 *
 * Can be used to travers the explain result.
 *
 * Eg:
 *
 * $explain->getChild(0)->getScore()
 *
 * @autho Timo Schmidt <timo.schmidt@aoemedia.deA
 */
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
	 * @return \SolrExplain\Domain\Explanation\ExplainNode
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
	 * Method to retrieve the root node of the explain.
	 *
	 * @return \SolrExplain\Domain\Explanation\ExplainNode
	 */
	public function getRootNode() {
		return $this->rootNode;
	}

	/**
	 * Method to set the corresponding document id.
	 *
	 * @param string $documentId
	 */
	public function setDocumentId($documentId) {
		$this->documentId = $documentId;
	}

	/**
	 * Document id where this explanation belongs to.
	 *
	 * @return string
	 */
	public function getDocumentId() {
		return $this->documentId;
	}

	/**
	 * Method to set a single attribute
	 *
	 * Eg: :query
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function setAttribute($key, $value) {
		$this->attributes->offsetSet($key,$value);
	}

	/**
	 * Return the value for a single attribute.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getAttribute($key) {
		return $this->attributes->offsetGet($key);
	}
}
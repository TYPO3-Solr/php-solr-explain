<?php

namespace Solr\Domain\Result\Explanation;

/**
 * Root object of the parse explain.
 *
 * Can be used to travers the explain result.
 *
 * Eg:
 *
 * $explain->getChild(0)->getScore()
 *
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
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
	 * @var \Solr\Domain\Result\Explanation\Nodes\Explain
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
	 * @return \ArrayObject<\Solr\Domain\Result\Explanation\ExplainNode>
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * @param $index
	 * @return \Solr\Domain\Result\Explanation\Nodes\Explain
	 */
	public function getChild($index) {
		return $this->children[$index];
	}

	/**
	 * @param \Solr\Domain\Result\Explanation\Nodes\Explain $rootNode
	 */
	public function setRootNode($rootNode) {
		$this->rootNode = $rootNode;
	}

	/**
	 * Method to retrieve the root node of the explain.
	 *
	 * @return \Solr\Domain\Result\Explanation\Nodes\Explain
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
<?php

namespace SolrExplain\Domain\Explanation;

class Explain {

	/**
	 * @var float
	 */
	protected $score = 0.0;

	/**
	 * @var \ArrayObject
	 */
	protected $children = null;

	/**
	 * @var \SolrExplain\Domain\Explanation\ExplainNode
	 */
	protected $rootNode = null;

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
}
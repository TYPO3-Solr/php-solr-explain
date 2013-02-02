<?php

namespace SolrExplain\Domain\Explanation;

class ExplainNode {

	const NODE_TYPE_SUM = 1;

	const NODE_TYPE_MAX = 2;

	const NODE_TYPE_PRODUCT = 4;

	const NODE_TYPE_LEAF = 8;

	/**
	 * @var int
	 */
	protected $level = 0;

	/**
	 * @var string
	 */
	protected $content = '';

	/**
	 * @var float
	 */
	protected $score = 0.0;

	/**
	 * @var \ArrayObject
	 */
	protected $children = null;

	/**
	 * @var ExplainNode
	 */
	protected $parent = null;

	/**
	 * @param \SolrExplain\Domain\Explanation\ExplainNode $parent
	 */
	public function setParent($parent) {
		$this->parent = $parent;
	}

	/**
	 * @return \SolrExplain\Domain\Explanation\ExplainNode
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @return void
	 */
	public function __construct() {
		$this->children = new \ArrayObject();
	}

	/**
	 * @param string $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param int $level
	 */
	public function setLevel($level) {
		$this->level = $level;
	}

	/**
	 * @param \ArrayObject $children
	 */
	public function setChildren($children) {
		$this->children = $children;
	}

	/**
	 * @return \ArrayObject
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * @param $index
	 * @return mixed
	 */
	public function getChild($index) {
		return $this->children[$index];
	}

	/**
	 * @return int
	 */
	public function getLevel() {
		return $this->level;
	}

	/**
	 * @param float $score
	 */
	public function setScore($score) {
		$this->score = $score;
	}

	/**
	 * @return float
	 */
	public function getScore() {
		return $this->score;
	}
}
<?php

namespace SolrExplain\Domain\Explanation;

/**
 * Represents an node of the explain result provided by solr.
 *
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
 */
class ExplainNode {

	/**
	 * Different types of nodes that need to be handled different during calculation
 	 */
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
	 * @var int
	 */
	protected $nodeType = -1;

	/**
	 * @var string
	 */
	protected $fieldName = '*';

	/**
	 * @return void
	 */
	public function __construct() {
		$this->children = new \ArrayObject();
	}

	/**
	 * @return
	 */
	public function getAbsoluteImpactPercentage() {
		if($this->level == 0) {
			return 100.0;
		} else {
			if($this->getParent()->getNodeType() == self::NODE_TYPE_SUM) {
				$parentScore 			= $this->getParent()->getScore();
				$parentPercentage		= $this->getParent()->getAbsoluteImpactPercentage();

					//part of this node relative to the parent
				$scorePercentageToParent	= (100 / $parentScore) * $this->getScore();
				return ($parentPercentage / 100) * $scorePercentageToParent;
			}

			if($this->getParent()->getNodeType() == self::NODE_TYPE_MAX) {
				$neighboors = $this->getParent()->getChildren();
				foreach($neighboors as $neighbor) {
					if($neighbor != $this && $neighbor->getScore() > $this->getScore()) {
						return 0;
					} else {
							//when this node has the highest score we "inherit" the parents score
						return $this->getParent()->getAbsoluteImpactPercentage();
					}
				}
			}

			if($this->getParent()->getNodeType() == self::NODE_TYPE_PRODUCT) {
				$neighborScorePart = array();

				foreach($this->getParent()->getChildren() as $neighbor) {
					if($neighbor != $this) {
						$neighborScore = $neighbor->getScore();
						$neighborScorePart[] = ($neighborScore * $neighborScore) + 1;
					}

				}

				$myScorePart = (($this->getScore() * $this->getScore()) + 1);
				$scoreSum 	= array_sum($neighborScorePart) + $myScorePart;
				return ($this->getParent()->getAbsoluteImpactPercentage() / $scoreSum) * $myScorePart;
			}
		}
	}

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

	/**
	 * @param int $nodeType
	 */
	public function setNodeType($nodeType) {
		$this->nodeType = $nodeType;
	}

	/**
	 * @return int
	 */
	public function getNodeType() {
		return $this->nodeType;
	}

	/**
	 * @param string $fieldName
	 */
	public function setFieldName($fieldName) {
		$this->fieldName = $fieldName;
	}

	/**
	 * @return string
	 */
	public function getFieldName() {
		return $this->fieldName;
	}

	/**
	 * This method can be used to traverse all child nodes.
	 *
	 * @param \SolrExplain\Domain\Explanation\Visitors\ExplainNodeVisitorInterface $visitor
	 */
	public function visitNodes(\SolrExplain\Domain\Explanation\Visitors\ExplainNodeVisitorInterface $visitor) {
		$visitor->visit($this);
		foreach($this->getChildren() as $child) {
			$child->visitNodes($visitor);
		}
	}
}
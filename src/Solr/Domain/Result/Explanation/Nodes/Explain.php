<?php

namespace Solr\Domain\Result\Explanation\Nodes;

/**
 * Represents an node of the explain result provided by solr.
 *
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
 */
class Explain {

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
				$neighbors 		= $this->getParent()->getChildren();
				$tieBreaker		= $this->getParent()->getTieBreaker();

				$parentScore				= $this->getParent()->getScore();
				$parentScorePart 			= ($this->getScore()/$parentScore) * (100);
				$parentScorePartPercentage 	= $this->getParent()->getAbsoluteImpactPercentage() / 100.0;

				$isMaxNode = true;
				foreach($neighbors as $neighbor) {
					if($neighbor != $this && $neighbor->getScore() > $this->getScore()) {
						$isMaxNode = false;
						break;
					}
				}

				if($tieBreaker > 0) {
					if($isMaxNode) {
						return $parentScorePart * $parentScorePartPercentage;
					} else {
						return $parentScorePart * $parentScorePartPercentage * $tieBreaker;
					}
				} else {
					if($isMaxNode) {
						return $this->getParent()->getAbsoluteImpactPercentage();
					} else {
						return 0;
					}
				}
			}

			if($this->getParent()->getNodeType() == self::NODE_TYPE_PRODUCT) {
				$neighbors 			= $this->getParent()->getChildren();

				if($neighbors->count() > 1) {
					$neighborScorePart 	= array();
					$parentPercentage	= $this->getParent()->getAbsoluteImpactPercentage();

					foreach($neighbors as $neighbor) {
						if($neighbor != $this) {
							$neighborScore = $neighbor->getScore();
							$neighborScorePart[] = $neighborScore;
						}
					}

					$scoreSum 	= array_sum($neighborScorePart) + $this->getScore();

					$multiplier =  100 / $scoreSum;
					$parentMultiplier = $parentPercentage / 100;
					return $this->getScore() * $multiplier * $parentMultiplier;
				} else {
						//when only one leaf in product is present we can inherit the parent score
					return $this->getParent()->getAbsoluteImpactPercentage();
				}
			}
		}
	}

	/**
	 * @param \Solr\Domain\Result\Explanation\Nodes\Explain $parent
	 */
	public function setParent($parent) {
		$this->parent = $parent;
	}

	/**
	 * @return \Solr\Domain\Result\Explanation\Nodes\Explain
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
	 * @return \Solr\Domain\Result\Explanation\Nodes\Explain
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
	protected function setNodeType($nodeType) {
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
	 * @param \Solr\Domain\Result\Explanation\Visitors\ExplainNodeVisitorInterface $visitor
	 */
	public function visitNodes(\Solr\Domain\Result\Explanation\Visitors\ExplainNodeVisitorInterface $visitor) {
		$visitor->visit($this);
		foreach($this->getChildren() as $child) {
			$child->visitNodes($visitor);
		}
	}
}
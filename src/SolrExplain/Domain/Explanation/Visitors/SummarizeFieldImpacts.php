<?php

namespace SolrExplain\Domain\Explanation\Visitors;

/**
 *
 * @autho Timo Schmidt <timo.schmidt@aoemedia.deA
 */
class SummarizeFieldImpacts implements \SolrExplain\Domain\Explanation\Visitors\ExplainNodeVisitorInterface {

	/**
	 * @var float
	 */
	protected $sums = array();

	/**
	 * @param \SolrExplain\Domain\Explanation\ExplainNode $node
	 * @return mixed|void
	 */
	public function visit(\SolrExplain\Domain\Explanation\ExplainNode $node) {
		if($node->getNodeType() == $node::NODE_TYPE_LEAF) {
			$fieldName = $node->getParent()->getFieldName();
			if(!isset($this->sums[$fieldName])) {
				$this->sums[$fieldName] = 0;
			}

			$this->sums[$fieldName] += $node->getAbsoluteImpactPercentage();
		}
	}

	/**
	 * @return array<string>
	 */
	public function getRelevantFieldNames() {
		return array_keys($this->sums);
	}

	/**
	 * @param $fieldName
	 * @return float
	 */
	public function getFieldImpact($fieldName) {
		if(!array_key_exists($fieldName,$this->sums)) {
			return 0.0;
		}

		return $this->sums[$fieldName];
	}
}
<?php

namespace SolrExplain\Domain\Explanation\Visitors;

class SummarizeLeafImpacts implements \SolrExplain\Domain\Explanation\Visitors\ExplainNodeVisitorInterface {

	/**
	 * @var float
	 */
	protected $sum = 0.0;

	/**
	 * @param \SolrExplain\Domain\Explanation\ExplainNode $node
	 * @return mixed|void
	 */
	public function visit(\SolrExplain\Domain\Explanation\ExplainNode $node) {
		if($node->getNodeType() == $node::NODE_TYPE_LEAF) {
			$this->sum += $node->getAbsoluteImpactPercentage();
		}
	}

	/**
	 * @return float
	 */
	public function getSum() {
		return $this->sum;
	}
}
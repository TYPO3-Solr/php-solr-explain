<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Visitors;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Nodes\Explain;

/**
 * This visitor is used to summarize the impacts of all leaf
 * nodes. For a normal explain this should be 100% and it is used
 * for verification.
 */
class SummarizeLeafImpacts implements ExplainNodeVisitorInterface {

	/**
	 * @var float
	 */
	protected $sum = 0.0;

	/**
	 * @param Explain $node
	 * @return mixed|void
	 */
	public function visit(Explain $node) {
		if($node->getNodeType() == $node::NODE_TYPE_LEAF) {
			$this->sum += $node->getAbsoluteImpactPercentage();
		}
	}

	/**
	 * @return float
	 */
	public function getSum() {
		return round($this->sum,1);
	}
}
<?php

namespace SolrExplain\Domain\Explanation\Visitors;

/**
 * This visitor is used to summarize the impacts of all leaf
 * nodes. For a normal explain this should be 100% and it is used
 * for verification.
 *
 * @autho Timo Schmidt <timo.schmidt@aoemedia.deA
 */
class SummarizeLeafImpacts implements \SolrExplain\Domain\Explanation\Visitors\ExplainNodeVisitorInterface {

	/**
	 * @var float
	 */
	protected $sum = 0.0;

	/**
	 * @param \SolrExplain\Domain\Explanation\Nodes\Explain $node
	 * @return mixed|void
	 */
	public function visit(\SolrExplain\Domain\Explanation\Nodes\Explain $node) {
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
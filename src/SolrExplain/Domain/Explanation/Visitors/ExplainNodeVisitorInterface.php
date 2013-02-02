<?php

namespace SolrExplain\Domain\Explanation\Visitors;

interface ExplainNodeVisitorInterface {

	/**
	 * @param \SolrExplain\Domain\Explanation\ExplainNode $node
	 * @return mixed
	 */
	public function visit(\SolrExplain\Domain\Explanation\ExplainNode $node);
}
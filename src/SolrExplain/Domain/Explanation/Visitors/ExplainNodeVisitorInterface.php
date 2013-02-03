<?php

namespace SolrExplain\Domain\Explanation\Visitors;

/**
 * Visitor interface, needs to be implemented by a node visitor.
 *
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
 */

interface ExplainNodeVisitorInterface {

	/**
	 * @param \SolrExplain\Domain\Explanation\Nodes\Explain $node
	 * @return mixed
	 */
	public function visit(\SolrExplain\Domain\Explanation\Nodes\Explain $node);
}
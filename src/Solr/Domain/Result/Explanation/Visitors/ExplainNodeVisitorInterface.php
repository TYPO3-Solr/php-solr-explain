<?php

namespace Solr\Domain\Result\Explanation\Visitors;

/**
 * Visitor interface, needs to be implemented by a node visitor.
 *
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
 */

interface ExplainNodeVisitorInterface {

	/**
	 * @param \Solr\Domain\Result\Explanation\Nodes\Explain $node
	 * @return mixed
	 */
	public function visit(\Solr\Domain\Result\Explanation\Nodes\Explain $node);
}
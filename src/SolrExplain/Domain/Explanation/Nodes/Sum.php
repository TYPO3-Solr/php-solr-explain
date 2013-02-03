<?php

namespace SolrExplain\Domain\Explanation\Nodes;

class Sum extends \SolrExplain\Domain\Explanation\Nodes\Explain {

	/**
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->setNodeType(self::NODE_TYPE_SUM);
	}
}

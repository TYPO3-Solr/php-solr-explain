<?php

namespace SolrExplain\Domain\Explanation\Nodes;

class Leaf extends \SolrExplain\Domain\Explanation\Nodes\Explain {

	/**
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->setNodeType(self::NODE_TYPE_LEAF);
	}

}
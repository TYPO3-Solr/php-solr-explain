<?php

namespace SolrExplain\Domain\Explanation\Nodes;

class Max extends \SolrExplain\Domain\Explanation\Nodes\Explain {

	/**
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->setNodeType(self::NODE_TYPE_MAX);
	}

	/**
	 * @return float
	 */
	public function getTieBreaker() {

		$matches = array();
		preg_match('~plus (?<tiebreaker>.*) times~',$this->getContent(),$matches);
		if(isset($matches['tiebreaker'])) {
			return $matches['tiebreaker'];
		}

		return 0;
	}

}
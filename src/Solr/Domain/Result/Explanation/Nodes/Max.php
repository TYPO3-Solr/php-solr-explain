<?php

namespace Solr\Domain\Result\Explanation\Nodes;

class Max extends \Solr\Domain\Result\Explanation\Nodes\Explain {

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
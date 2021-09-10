<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Nodes;

class Max extends Explain
{

	/**
	 * @return void
	 */
	public function __construct()
    {
		parent::__construct();
		$this->setNodeType(self::NODE_TYPE_MAX);
	}

	/**
	 * @return float
	 */
	public function getTieBreaker()
    {

		$matches = [];
		preg_match('~plus (?<tiebreaker>.*) times~',$this->getContent(),$matches);
		if(isset($matches['tiebreaker'])) {
			return $matches['tiebreaker'];
		}

		return 0;
	}

}
<?php

namespace SolrExplain\Tests\Domain\Explanation\Nodes;


class MaxTestCase extends \SolrExplain\Tests\AbstractExplanationTestCase {

	/**
	 */
	public function testGetTieBreaker() {
		$maxNode = new \SolrExplain\Domain\Explanation\Nodes\Max();
		$maxNode->setContent('8.040816E-4 = (MATCH) max plus 0.7 times others of:');
		$this->assertEquals(0.7, $maxNode->getTieBreaker());
	}
}
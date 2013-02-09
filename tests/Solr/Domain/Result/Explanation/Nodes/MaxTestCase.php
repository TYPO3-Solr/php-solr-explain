<?php

namespace Solr\Tests\Domain\Result\Explanation\Nodes;


class MaxTestCase extends \Solr\Tests\Domain\Result\Explanation\AbstractExplanationTestCase {

	/**
	 */
	public function testGetTieBreaker() {
		$maxNode = new \Solr\Domain\Result\Explanation\Nodes\Max();
		$maxNode->setContent('8.040816E-4 = (MATCH) max plus 0.7 times others of:');
		$this->assertEquals(0.7, $maxNode->getTieBreaker());
	}
}
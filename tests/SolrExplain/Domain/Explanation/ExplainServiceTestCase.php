<?php

namespace SolrExplain\Tests\Domain\Explanation;

/**
 * Test for the top level service class.
 *
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
 */
class ExplainServiceTestCase extends \SolrExplain\Tests\AbstractExplanationTestCase {

	/**
	 * @test
	 */
	public function testFixture1() {
		$content 	= $this->getFixtureContent('3.0.001.txt');
		$result 	= \SolrExplain\Domain\Explanation\ExplainService::getFieldImpactsFromRawContent(
			$content,
			'foo',
			'bar'
		);

		$this->assertEquals(array('name' => 100), $result);
	}
}
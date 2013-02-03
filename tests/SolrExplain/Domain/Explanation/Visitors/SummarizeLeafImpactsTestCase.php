<?php

namespace SolrExplain\Tests\Domain\Explanation;

/**
 *
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
 */
class SummarizeLeafImpactsTestCase extends \SolrExplain\Tests\AbstractExplanationTestCase{

	/**
	 * @return \SolrExplain\Domain\Explanation\Explain
	 */
	protected function getExplain($filename) {
		$fileContent = $this->getFixtureContent($filename.".txt");
		$content = new \SolrExplain\Domain\Explanation\Content($fileContent);
		$metaData = new \SolrExplain\Domain\Explanation\MetaData('P_164345','auto');
		$parser = new \SolrExplain\Domain\Explanation\Parser();

		$parser->injectExplain(new \SolrExplain\Domain\Explanation\Explain());
		$explain = $parser->parse($content,$metaData);

		return $explain;
	}

	/**
	 * @return array
	 */
	public function leafSumFixtureNameDataProvider() {
		return array(
			array('3.0.001'),
			array('3.0.002'),
			array('3.0.003'),
			array('3.0.004'),
			array('3.0.005'),
			array('3.4.001'),
			array('3.4.002'),
			array('3.4.003'),
			array('3.4.004'),
			array('3.4.005'),
			array('3.4.006'),
			array('3.4.007'),
			array('3.4.008'),
			array('3.4.009'),
			array('3.4.010'),
			array('3.4.011'),
			array('3.4.012'),
			array('3.4.013'),
			array('3.4.014'),
			array('3.4.015'),
			array('3.4.016'),
			array('3.4.017'),
				//contains invalid content therefore no overall impact of 100 expected
			array('3.4.018',0.0),
			array('3.4.019'),
			array('3.4.020'),
			array('3.4.021'),
			array('3.4.022'),
			array('3.4.023'),
			array('3.4.024'),
			array('3.4.025'),
			array('3.4.026'),
			array('3.4.027'),
			array('3.4.028'),
			array('4.0.001'),
			array('complex'),
			array('custom.tieBreaker')
		);
	}

	/**
	 * The sum for all leaf impacts in percetage should be 100%.
	 *
	 * This testcase is used to test this for some fixture files.
	 *
	 * @test
	 * @dataProvider leafSumFixtureNameDataProvider
	 */
	public function verifyFixtureLeafImpactSum($fixtureName, $expectedImpactSum = 100) {
		$explain = $this->getExplain($fixtureName);
		$visitor = new \SolrExplain\Domain\Explanation\Visitors\SummarizeLeafImpacts();
		$explain->getRootNode()->visitNodes($visitor);
		$this->assertEquals($expectedImpactSum, $visitor->getSum());
	}

}
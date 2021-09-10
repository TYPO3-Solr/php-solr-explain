<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Explanation;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Content;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\ExplainResult;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\MetaData;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Parser;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Visitors\SummarizeLeafImpacts;
use ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Explanation\AbstractExplanationTestCase;

class SummarizeLeafImpactsTestCase extends AbstractExplanationTestCase
{

	/**
	 * @return ExplainResult
	 */
	protected function getExplain($filename)
    {
		$fileContent = $this->getFixtureContent($filename.".txt");
		$content = new Content($fileContent);
		$metaData = new MetaData('P_164345','auto');
		$parser = new Parser();

		$parser->injectExplainResult(new ExplainResult());
		$explain = $parser->parse($content,$metaData);

		return $explain;
	}

	/**
	 * @return array
	 */
	public function leafSumFixtureNameDataProvider()
    {
		return [
			['3.0.001'],
			['3.0.002'],
			['3.0.003'],
			['3.0.004'],
			['3.0.005'],
			['3.4.001'],
			['3.4.002'],
			['3.4.003'],
			['3.4.004'],
			['3.4.005'],
			['3.4.006'],
			['3.4.007'],
			['3.4.008'],
			['3.4.009'],
			['3.4.010'],
			['3.4.011'],
			['3.4.012'],
			['3.4.013'],
			['3.4.014'],
			['3.4.015'],
			['3.4.016'],
			['3.4.017'],
				//contains invalid content therefore no overall impact of 100 expected
			['3.4.018',0.0],
			['3.4.019'],
			['3.4.020'],
			['3.4.021'],
			['3.4.022'],
			['3.4.023'],
			['3.4.024'],
			['3.4.025'],
			['3.4.026'],
			['3.4.027'],
			['3.4.028'],
			['4.0.001'],
			['complex'],
			['custom.tieBreaker']
        ];
	}

	/**
	 * The sum for all leaf impacts in percetage should be 100%.
	 *
	 * This testcase is used to test this for some fixture files.
	 *
	 * @test
	 * @dataProvider leafSumFixtureNameDataProvider
	 */
	public function verifyFixtureLeafImpactSum($fixtureName, $expectedImpactSum = 100)
    {
		$explain = $this->getExplain($fixtureName);
		$visitor = new SummarizeLeafImpacts();
		$explain->getRootNode()->visitNodes($visitor);
		$this->assertEquals($expectedImpactSum, $visitor->getSum());
	}

}
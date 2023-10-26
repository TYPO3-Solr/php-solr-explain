<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Explanation;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Nodes\Explain;

/**
 * Testcases for the php port of solr explain.
 */
class ExplanationSolr3TestCase extends AbstractExplanationTestCase
{

	/**
	 * @test
	 */
	public function testFixture001GetScore()
    {
		$explain = $this->getExplainFromFixture('3.0.001');

		$this->assertNotNull($explain);
		$this->assertEquals(1, $explain->getChildren()->count());
		$this->assertEquals(2,$explain->getChild(0)->getChildren()->count());
		$this->assertEquals(2,$explain->getChild(0)->getParent()->getChild(0)->getChildren()->count());
		$this->assertEquals(0.8621642, $explain->getRootNode()->getScore());
		$this->assertEquals(0.8621642,$explain->getChild(0)->getScore());
	}

	/**
	 * @test
	 */
	public function testFixture001GetImpact()
    {
		$explain = $this->getExplainFromFixture('3.0.001');

		$this->assertNotNull($explain);
		$this->assertEquals(0.8621642, $explain->getRootNode()->getScore());
		$this->assertEquals(0.8621642,$explain->getChild(0)->getScore());
		$this->assertEquals(0.4310821,$explain->getChild(0)->getChild(0)->getScore());

		$this->assertEquals(100.0,$explain->getRootNode()->getAbsoluteImpactPercentage());

		$this->assertEquals(Explain::NODE_TYPE_SUM,$explain->getRootNode()->getNodeType());
		$this->assertEquals(Explain::NODE_TYPE_SUM,$explain->getRootNode()->getChild(0)->getNodeType());

		$this->assertEquals(100.0,$explain->getRootNode()->getChild(0)->getAbsoluteImpactPercentage());

			//the sum nodes
		$this->assertEquals(50.0,$explain->getRootNode()->getChild(0)->getChild(0)->getAbsoluteImpactPercentage());
		$this->assertEquals(50.0,$explain->getRootNode()->getChild(0)->getChild(1)->getAbsoluteImpactPercentage());

			//the max node
		$this->assertEquals(50.0,$explain->getRootNode()->getChild(0)->getChild(0)->getChild(0)->getAbsoluteImpactPercentage());

			//	0.5044475 + 0.8545628 = 1,3590103
			// 100 / 1,3590103 = 73,582959599
			// 0.5044475 * 73,582959599 = 37,118740012 / 2 => 18,559370006
			// 0.8545628 * 73,582959599 = 62,881259988 / 2 => 31,440629994

		$this->assertEquals(18.55937000624646,$explain->getRootNode()->getChild(0)->getChild(0)->getChild(0)->getChild(0)->getAbsoluteImpactPercentage());
		$this->assertEquals(31.440629993753543,$explain->getRootNode()->getChild(0)->getChild(0)->getChild(0)->getChild(1)->getAbsoluteImpactPercentage());
		$this->assertEquals(Explain::NODE_TYPE_MAX,$explain->getRootNode()->getChild(0)->getChild(0)->getNodeType());
	}

	/**
	 * @test
	 */
	public function testFixture002()
    {
		$explain = $this->getExplainFromFixture('3.0.002');

		$this->assertNotNull($explain);
		$this->assertEquals(1.6506158, $explain->getRootNode()->getScore());
		$this->assertEquals(2, $explain->getChildren()->count());
	}

	/**
	 * @test
	 */
	public function testFixture003()
    {
		$explain = $this->getExplainFromFixture('3.0.003');

		$this->assertNotNull($explain);
		$this->assertEquals(36.50278,$explain->getRootNode()->getScore());
		$this->assertEquals(2, $explain->getChildren()->count());
	}

	/**
	 * @test
	 */
	public function testFixture004()
    {
		$explain = $this->getExplainFromFixture('3.0.004');

		$this->assertNotNull($explain);
		$this->assertEquals(0.524427,$explain->getRootNode()->getScore());
		$this->assertEquals(2, $explain->getChildren()->count());
	}

	/**
	 * @test
	 */
	public function testFixture005()
    {
		$explain = $this->getExplainFromFixture('3.0.005');

		$this->assertNotNull($explain);
		$this->assertEquals(5.8746934,$explain->getRootNode()->getScore());
		$this->assertEquals(2, $explain->getChildren()->count());
	}
}

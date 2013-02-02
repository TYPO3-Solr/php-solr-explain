<?php

namespace SolrExplain\Tests\Domain\Explanation;

/**
 * Testcases for the php port of solr explain.
 *
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
 */
class ExplanationSolr3TestCase extends \SolrExplain\Tests\AbstractExplanationTestCase{

	/**
	 * @return void
	 */
	public function setUp() {}

	/**
	 * @return void
	 */
	public function tearDown() {}

	/**
	 *
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
	 * @test
	 */
	public function testFixture001GetScore() {
		$explain = $this->getExplain('3.0.001');

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
	public function testFixture001GetImpact() {
		$explain = $this->getExplain('3.0.001');

		$this->assertNotNull($explain);
		$this->assertEquals(0.8621642, $explain->getRootNode()->getScore());
		$this->assertEquals(0.8621642,$explain->getChild(0)->getScore());
		$this->assertEquals(0.4310821,$explain->getChild(0)->getChild(0)->getScore());

		$this->assertEquals(100.0,$explain->getRootNode()->getAbsoluteImpactPercentage());

		$this->assertEquals(\SolrExplain\Domain\Explanation\ExplainNode::NODE_TYPE_SUM,$explain->getRootNode()->getNodeType());
		$this->assertEquals(\SolrExplain\Domain\Explanation\ExplainNode::NODE_TYPE_SUM,$explain->getRootNode()->getChild(0)->getNodeType());

		$this->assertEquals(100.0,$explain->getRootNode()->getChild(0)->getAbsoluteImpactPercentage());

			//the sum nodes
		$this->assertEquals(50.0,$explain->getRootNode()->getChild(0)->getChild(0)->getAbsoluteImpactPercentage());
		$this->assertEquals(50.0,$explain->getRootNode()->getChild(0)->getChild(1)->getAbsoluteImpactPercentage());

			//the max node
		$this->assertEquals(50.0,$explain->getRootNode()->getChild(0)->getChild(0)->getChild(0)->getAbsoluteImpactPercentage());

			//	(0.5044475 * 0.5044475) + 1 = 1,25446728
			// (0.8545628 * 0.8545628) + 1 = 1,730277579
			// 2,984744859
			// (100 / 2,984744859 ) * 1,25446728 = 42,02929695 => 21.014648476661
			// (100 / 2,984744859 ) * 1,730277579 = 57,97070305 => 28.985351523339
		$this->assertEquals(21.0146484766615,$explain->getRootNode()->getChild(0)->getChild(0)->getChild(0)->getChild(0)->getAbsoluteImpactPercentage());
		$this->assertEquals(28.985351523339,$explain->getRootNode()->getChild(0)->getChild(0)->getChild(0)->getChild(1)->getAbsoluteImpactPercentage());
		$this->assertEquals(\SolrExplain\Domain\Explanation\ExplainNode::NODE_TYPE_MAX,$explain->getRootNode()->getChild(0)->getChild(0)->getNodeType());
	}

	/**
	 * @test
	 */
	public function testCanSummerizeLeafNodes() {
		$explain = $this->getExplain('3.0.001');
		$visitor = new \SolrExplain\Domain\Explanation\Visitors\SummarizeLeafImpacts();
		$explain->getRootNode()->visitNodes($visitor);
		$this->assertEquals(100.0, $visitor->getSum());
	}

	/**
	 * @test
	 */
	public function testFixture002() {
		$explain = $this->getExplain('3.0.002');

		$this->assertNotNull($explain);
		$this->assertEquals(1.6506158, $explain->getRootNode()->getScore());
		$this->assertEquals(2, $explain->getChildren()->count());
	}

	/**
	 * @test
	 */
	public function testFixture003() {
		$explain = $this->getExplain('3.0.003');

		$this->assertNotNull($explain);
		$this->assertEquals(36.50278,$explain->getRootNode()->getScore());
		$this->assertEquals(2, $explain->getChildren()->count());
	}

	/**
	 * @test
	 */
	public function testFixture004() {
		$explain = $this->getExplain('3.0.004');

		$this->assertNotNull($explain);
		$this->assertEquals(0.524427,$explain->getRootNode()->getScore());
		$this->assertEquals(2, $explain->getChildren()->count());
	}

	/**
	 * @test
	 */
	public function testFixture005() {
		$explain = $this->getExplain('3.0.005');

		$this->assertNotNull($explain);
		$this->assertEquals(5.8746934,$explain->getRootNode()->getScore());
		$this->assertEquals(2, $explain->getChildren()->count());
	}
}
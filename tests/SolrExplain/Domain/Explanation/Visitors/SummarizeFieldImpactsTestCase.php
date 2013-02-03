<?php

namespace SolrExplain\Tests\Domain\Explanation;

/**
 *
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
 */
class SummarizeFieldImpactsTestCase extends \SolrExplain\Tests\AbstractExplanationTestCase{

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
	 * @test
	 */
	public function testCanSummarizeFieldImpactFixture001() {
		$explain = $this->getExplain('3.0.001');
		$visitor = new \SolrExplain\Domain\Explanation\Visitors\SummarizeFieldImpacts();
		$explain->getRootNode()->visitNodes($visitor);

		$this->assertEquals(100.0,$visitor->getFieldImpact('name'));
		$this->assertEquals(array('name'),$visitor->getRelevantFieldNames());
	}

	/**
	 * @test
	 */
	public function testCanSummarizeFieldImpactFixture003() {
		$explain = $this->getExplain('3.0.003');
		$visitor = new \SolrExplain\Domain\Explanation\Visitors\SummarizeFieldImpacts();
		$explain->getRootNode()->visitNodes($visitor);

		$this->assertEquals(95.756597168764,$visitor->getFieldImpact('price'));
		$this->assertEquals(array('name','manu','price'),$visitor->getRelevantFieldNames());
	}

	/**
	 * @test
	 */
	public function testCanSummarizeFieldImpactFixture004() {
		$explain = $this->getExplain('3.0.004');
		$visitor = new \SolrExplain\Domain\Explanation\Visitors\SummarizeFieldImpacts();
		$explain->getRootNode()->visitNodes($visitor);

		$this->assertEquals(100.0,$visitor->getFieldImpact('name'));
		$this->assertEquals(array('name','price'),$visitor->getRelevantFieldNames());
	}

	/**
	 * @test
	 */
	public function testCanSummarizeCustomTieBreakerFixture() {
		$this->markTestSkipped('Skipped');
		$explain = $this->getExplain('custom.tieBreaker');
		$visitor = new \SolrExplain\Domain\Explanation\Visitors\SummarizeFieldImpacts();
		$explain->getRootNode()->visitNodes($visitor);

		$this->assertEquals(array('expandedcontent','content','doctype'),$visitor->getRelevantFieldNames());

		$this->assertEquals(47.9,round($visitor->getFieldImpact('doctype'),1));
		$this->assertEquals(47.9,$visitor->getFieldImpact('expandedcontent'));
		$this->assertEquals(4.2,$visitor->getFieldImpact('content'));
	}
}
<?php

namespace SolrExplain\Tests\Domain\Explanation;

/**
 * Testcases for the php port of solr explain for solr version 3.4 responses.
 *
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
 */
class ExplanationSolr34TestCase extends \SolrExplain\Tests\AbstractExplanationTestCase{

	/**
	 * @return void
	 */
	public function setUp() {}

	/**
	 * @return void
	 */
	public function tearDown() {}

	/**
	 * @param $filename
	 * @return \SolrExplain\Domain\Explanation\Explain
	 */
	protected function getFixture($filename) {
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
	public function testFixture001() {
		$explain = $this->getFixture('3.4.001');

		$this->assertNotNull($explain);
		$this->assertEquals('P_164345',$explain->getDocumentId());
		$this->assertEquals(1.0,$explain->getRootNode()->getScore());
		$this->assertEquals(1,$explain->getChildren()->count());
		$this->assertEquals(0,$explain->getChild(0)->getChildren()->count());
		$this->assertEquals(1.0,$explain->getChild(0)->getScore());
		$this->assertEquals('*:*',$explain->getAttribute(':query'));
	}

	/**
	 * @test
	 */
	public function testFixture002() {
		$explain = $this->getFixture('3.4.002');

		$this->assertNotNull($explain);
		$this->assertEquals('P_164345',$explain->getDocumentId());
		$this->assertEquals(1.0,$explain->getRootNode()->getScore());
		$this->assertEquals(2,$explain->getChildren()->count());

		$this->assertEquals(0,$explain->getChild(0)->getChildren()->count());
		$this->assertEquals(0,$explain->getChild(1)->getChildren()->count());

		$this->assertEquals(10.0,$explain->getChild(0)->getScore());
		$this->assertEquals(0.1,$explain->getChild(1)->getScore());

		$this->assertEquals('*:*^10.0',$explain->getAttribute(':query'));
	}

	/**
	 * @test
	 */
	public function testFixture003() {
		$explain = $this->getFixture('3.4.003');

		$this->assertNotNull($explain);
		$this->assertEquals('P_164345', $explain->getDocumentId());
		$this->assertEquals(100.48211, $explain->getRootNode()->getScore());

		$this->assertEquals(2, $explain->getChildren()->count());
		$this->assertEquals(2, $explain->getChild(0)->getChildren()->count());
		$this->assertEquals(3, $explain->getChild(1)->getChildren()->count());

		$this->assertEquals(0.99999994, $explain->getChild(0)->getScore());
		$this->assertEquals(100.48212, $explain->getChild(1)->getScore());
		
	}
}
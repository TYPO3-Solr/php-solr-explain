<?php
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Parser;

/**
 * This testcase should test if response from solr
 * can be parsed into an object structure.
 */
class ParserTestCase extends \ApacheSolrForTypo3\SolrExplain\Tests\AbstractSolrTest {

	/**
	 * @test
	 */
	public function testFixture001() {
		$content = $this->getFixtureContent("3.4.001.xml");
		$parser = new Parser();
		$result = $parser->parse($content);

		$this->assertEquals(17, $result->getCompleteResultCount());
		$this->assertEquals(9, $result->getQueryTime());
		$this->assertEquals(10, $result->getResultCount());

		$this->assertEquals("GB18030TEST",
			$result->getDocumentCollection()->getDocument(0)->getFieldByName('id')->getValue()
		);

		$this->assertEquals(
			array('electronics','hard drive'),
			$result->getDocumentCollection()->getDocument(1)->getFieldByName('cat')->getValue()
		);

		$expectedExplain = PHP_EOL.'1.0 = (MATCH) MatchAllDocsQuery, product of:'.PHP_EOL.'  1.0 = queryNorm'.PHP_EOL;
		$actualExplain = $result->getDocumentCollection()->getDocument(9)->getRawExplainData();
		$this->assertEquals($expectedExplain, $actualExplain);
	}

	/**
	 * @test
	 */
	public function testFixture004() {
		$content = $this->getFixtureContent("3.4.004.xml");
		$parser = new Parser();
		$result = $parser->parse($content);

		$this->assertEquals(2, $result->getQueryTime());
		$this->assertEquals(10, $result->getResultCount());

		$expectedExplain4 	= PHP_EOL."4.0 = (MATCH) MatchAllDocsQuery, product of:".PHP_EOL."  4.0 = queryNorm".PHP_EOL;
		$actualExplain 		= $result->getDocumentCollection()->getDocument(3)->getRawExplainData();

		$this->assertEquals($expectedExplain4, $actualExplain);
		$this->assertEquals(2.0, $result->getTiming()->getTimeSpend());
		$this->assertEquals(6, $result->getTiming()->getProcessingItems()->count());
	}

	/**
	 * @test
	 */
	public function testFixtureSolr4010() {
		$content = $this->getFixtureContent("4.0.010.xml");
		$parser = new Parser();
		$result = $parser->parse($content);

		$this->assertEquals(3, $result->getResultCount());
		$this->assertEquals(4, $result->getTiming()->getTimeSpend());
		$this->assertEquals("LuceneQParser",$result->getQueryParser());
	}
}
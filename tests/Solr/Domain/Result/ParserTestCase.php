<?php

/**
 * This testcase should test if response from solr
 * can be parsed into an object structure.
 *
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
 */
class ParserTestCase extends \Solr\Tests\AbstractSolrTest {

	/**
	 * @test
	 */
	public function testFixture001() {
		$content = $this->getFixtureContent("3.4.001.xml");
		$parser = new \Solr\Domain\Result\Parser();
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
		$parser = new \Solr\Domain\Result\Parser();
		$result = $parser->parse($content);

		$this->assertEquals(2, $result->getQueryTime());
		$this->assertEquals(10, $result->getResultCount());

		$expectedExplain4 	= PHP_EOL."4.0 = (MATCH) MatchAllDocsQuery, product of:".PHP_EOL."  4.0 = queryNorm".PHP_EOL;
		$actualExplain 		= $result->getDocumentCollection()->getDocument(3)->getRawExplainData();

		$this->assertEquals($expectedExplain4, $actualExplain);
	}
}

?>
<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Explanation;

/**
 * Testcases for the php port of solr explain for solr version 3.4 responses.
 */
class ExplanationSolr34TestCase extends AbstractExplanationTestCase{

	/**
	 * @test
	 */
	public function testFixture001()
    {
		$explain = $this->getExplainFromFixture('3.4.001');

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
	public function testFixture002()
    {
		$explain = $this->getExplainFromFixture('3.4.002');

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
	public function testFixture003()
    {
		$explain = $this->getExplainFromFixture('3.4.003');

		$this->assertNotNull($explain);
		$this->assertEquals('P_164345', $explain->getDocumentId());
		$this->assertEquals(100.48211, $explain->getRootNode()->getScore());

		$this->assertEquals(2, $explain->getChildren()->count());
		$this->assertEquals(2, $explain->getChild(0)->getChildren()->count());
		$this->assertEquals(3, $explain->getChild(1)->getChildren()->count());

		$this->assertEquals(0.99999994, $explain->getChild(0)->getScore());
		$this->assertEquals(100.48212, $explain->getChild(1)->getScore());
	}

	public function testFixture004()
    {
		$explain = $this->getExplainFromFixture('3.4.004');

		$this->assertNotNull($explain);
		$this->assertEquals('P_164345', $explain->getDocumentId());
		$this->assertEquals(0.0,$explain->getRootNode()->getScore());
		$this->assertEquals(3,$explain->getChildren()->count());
		$this->assertEquals(0, $explain->getChild(0)->getChildren()->count());
		$this->assertEquals(0, $explain->getChild(1)->getChildren()->count());
		$this->assertEquals(0, $explain->getChild(2)->getChildren()->count());

		$this->assertEquals(3.8332133, $explain->getChild(1)->getScore());
		$this->assertEquals(32.0, $explain->getChild(2)->getScore());
	}

	/**
	 * @test
	 */
	public function testFixture005()
    {
		$explain = $this->getExplainFromFixture('3.4.005');

		$this->assertNotNull($explain);
		$this->assertEquals('P_164345', $explain->getDocumentId());
		$this->assertEquals(142.10316,$explain->getRootNode()->getScore());
		$this->assertEquals(2,$explain->getChildren()->count());
		$this->assertEquals(2,$explain->getChild(0)->getChildren()->count());
		$this->assertEquals(2,$explain->getChild(1)->getChildren()->count());

		$this->assertEquals(71.05158,$explain->getChild(0)->getScore());
		$this->assertEquals(71.05158,$explain->getChild(1)->getScore());
	}

	public function testFixture006()
    {
		$explain = $this->getExplainFromFixture('3.4.006');

		$this->assertNotNull($explain);
		$this->assertEquals('P_164345', $explain->getDocumentId());
		$this->assertEquals(0.0, $explain->getRootNode()->getScore());
		$this->assertEquals(2, $explain->getChildren()->count());
		$this->assertEquals(2, $explain->getChild(0)->getChildren()->count());
		$this->assertEquals(0, $explain->getChild(1)->getChildren()->count());

		$this->assertEquals(63.675232, $explain->getChild(0)->getScore());
		$this->assertEquals(0.0, $explain->getChild(1)->getScore());
	}

	public function testFixture007()
    {
		$explain = $this->getExplainFromFixture('3.4.007');

		$this->assertNotNull($explain);
		$this->assertEquals('P_164345', $explain->getDocumentId());
		$this->assertEquals(200.96422,$explain->getRootNode()->getScore());
		$this->assertEquals(2,$explain->getChildren()->count());
		$this->assertEquals(2, $explain->getChild(0)->getChildren()->count());
		$this->assertEquals(3, $explain->getChild(1)->getChildren()->count());
		$this->assertEquals(0.99999994,$explain->getChild(0)->getScore());
		$this->assertEquals(200.96423, $explain->getChild(1)->getScore());

		$this->assertEquals('name:"Dell Widescreen"',$explain->getAttribute(':query'));
	}
}
<?php

namespace SolrExplain\Tests\Domain\Explanation;

/**
 * Testcase for solr 4.0 explain results.
 *
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
 */
class ExplanationSolr40TestCase extends \SolrExplain\Tests\AbstractExplanationTestCase{

	/**
	 * @return void
	 */
	public function setUp() {}

	/**
	 * @return void
	 */
	public function tearDown() {}

	/**
	 * @test
	 */
	public function testFixture001() {
		$explain = $this->getExplainFromFixture('4.0.001');

		$this->assertNotNull($explain);
		$this->assertEquals(0.6495038, $explain->getRootNode()->getScore());
		$this->assertEquals(1, $explain->getChildren()->count());
		$this->assertEquals(0.6495038, $explain->getChild(0)->getScore());
		$this->assertEquals(3, $explain->getChild(0)->getChildren()->count());
		$this->assertEquals(1, $explain->getChild(0)->getChild(0)->getChildren()->count());
		$this->assertEquals(0, $explain->getChild(0)->getChild(1)->getChildren()->count());
		$this->assertEquals(0, $explain->getChild(0)->getChild(2)->getChildren()->count());

		//TODO:
		// $this->assertEquals(4, $explain->getMetaData()->getVersion());
		// assert_equal('P_164345', explain.metadata[:id])
	}
}
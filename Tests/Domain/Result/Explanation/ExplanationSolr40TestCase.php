<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Explanation;

/**
 * Testcase for solr 4.0 explain results.
 */
class ExplanationSolr40TestCase extends AbstractExplanationTestCase
{
    /**
     * @test
     */
    public function testFixture001()
    {
        $explain = $this->getExplainFromFixture('4.0.001');

        self::assertNotNull($explain);
        self::assertEquals(0.6495038, $explain->getRootNode()->getScore());
        self::assertEquals(1, $explain->getChildren()->count());
        self::assertEquals(0.6495038, $explain->getChild(0)->getScore());
        self::assertEquals(3, $explain->getChild(0)->getChildren()->count());
        self::assertEquals(1, $explain->getChild(0)->getChild(0)->getChildren()->count());
        self::assertEquals(0, $explain->getChild(0)->getChild(1)->getChildren()->count());
        self::assertEquals(0, $explain->getChild(0)->getChild(2)->getChildren()->count());

        //TODO:
        // $this->assertEquals(4, $explain->getMetaData()->getVersion());
        // assert_equal('P_164345', explain.metadata[:id])
    }
}

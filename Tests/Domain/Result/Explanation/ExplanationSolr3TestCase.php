<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Explanation;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Nodes\Explain;

/**
 * Testcases for the php port of solr explain.
 */
class ExplanationSolr3TestCase extends AbstractExplanationTest
{
    /**
     * @test
     */
    public function testFixture001GetScore(): void
    {
        $explain = $this->getExplainFromFixture('3.0.001');

        self::assertEquals(1, $explain->getChildren()?->count());
        self::assertEquals(2, $explain->getChild(0)?->getChildren()?->count());
        self::assertEquals(2, $explain->getChild(0)?->getParent()?->getChild(0)?->getChildren()?->count());
        self::assertEquals(0.8621642, $explain->getRootNode()->getScore());
        self::assertEquals(0.8621642, $explain->getChild(0)?->getScore());
    }

    /**
     * @test
     */
    public function testFixture001GetImpact(): void
    {
        $explain = $this->getExplainFromFixture('3.0.001');

        self::assertEquals(0.8621642, $explain->getRootNode()->getScore());
        self::assertEquals(0.8621642, $explain->getChild(0)?->getScore());
        self::assertEquals(0.4310821, $explain->getChild(0)?->getChild(0)?->getScore());

        self::assertEquals(100.0, $explain->getRootNode()->getAbsoluteImpactPercentage());

        self::assertEquals(Explain::NODE_TYPE_SUM, $explain->getRootNode()->getNodeType());
        self::assertEquals(Explain::NODE_TYPE_SUM, $explain->getRootNode()->getChild(0)?->getNodeType());

        self::assertEquals(100.0, $explain->getRootNode()->getChild(0)?->getAbsoluteImpactPercentage());

        //the sum nodes
        self::assertEquals(50.0, $explain->getRootNode()->getChild(0)?->getChild(0)?->getAbsoluteImpactPercentage());
        self::assertEquals(50.0, $explain->getRootNode()->getChild(0)?->getChild(1)?->getAbsoluteImpactPercentage());

        //the max node
        self::assertEquals(50.0, $explain->getRootNode()->getChild(0)?->getChild(0)?->getChild(0)?->getAbsoluteImpactPercentage());

        //	0.5044475 + 0.8545628 = 1,3590103
        // 100 / 1,3590103 = 73,582959599
        // 0.5044475 * 73,582959599 = 37,118740012 / 2 => 18,559370006
        // 0.8545628 * 73,582959599 = 62,881259988 / 2 => 31,440629994

        self::assertEquals(18.55937000624646, $explain->getRootNode()->getChild(0)?->getChild(0)?->getChild(0)?->getChild(0)?->getAbsoluteImpactPercentage());
        self::assertEquals(31.440629993753543, $explain->getRootNode()->getChild(0)?->getChild(0)?->getChild(0)?->getChild(1)?->getAbsoluteImpactPercentage());
        self::assertEquals(Explain::NODE_TYPE_MAX, $explain->getRootNode()->getChild(0)?->getChild(0)?->getNodeType());
    }

    /**
     * @test
     */
    public function testFixture002(): void
    {
        $explain = $this->getExplainFromFixture('3.0.002');

        self::assertEquals(1.6506158, $explain->getRootNode()->getScore());
        self::assertEquals(2, $explain->getChildren()?->count());
    }

    /**
     * @test
     */
    public function testFixture003(): void
    {
        $explain = $this->getExplainFromFixture('3.0.003');

        self::assertEquals(36.50278, $explain->getRootNode()->getScore());
        self::assertEquals(2, $explain->getChildren()?->count());
    }

    /**
     * @test
     */
    public function testFixture004(): void
    {
        $explain = $this->getExplainFromFixture('3.0.004');

        self::assertEquals(0.524427, $explain->getRootNode()->getScore());
        self::assertEquals(2, $explain->getChildren()?->count());
    }

    /**
     * @test
     */
    public function testFixture005(): void
    {
        $explain = $this->getExplainFromFixture('3.0.005');

        self::assertEquals(5.8746934, $explain->getRootNode()->getScore());
        self::assertEquals(2, $explain->getChildren()?->count());
    }
}

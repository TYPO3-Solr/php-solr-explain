<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Explanation;

/**
 * Testcases for the php port of solr explain for solr version 3.4 responses.
 */
class ExplanationSolr34TestCase extends AbstractExplanationTest
{
    /**
     * @test
     */
    public function testFixture001(): void
    {
        $explain = $this->getExplainFromFixture('3.4.001');

        self::assertEquals('P_164345', $explain->getDocumentId());
        self::assertEquals(1.0, $explain->getRootNode()->getScore());
        self::assertEquals(1, $explain->getChildren()?->count());
        self::assertEquals(0, $explain->getChild(0)?->getChildren()?->count());
        self::assertEquals(1.0, $explain->getChild(0)?->getScore());
        self::assertEquals('*:*', $explain->getAttribute(':query'));
    }

    /**
     * @test
     */
    public function testFixture002(): void
    {
        $explain = $this->getExplainFromFixture('3.4.002');

        self::assertEquals('P_164345', $explain->getDocumentId());
        self::assertEquals(1.0, $explain->getRootNode()->getScore());
        self::assertEquals(2, $explain->getChildren()?->count());

        self::assertEquals(0, $explain->getChild(0)?->getChildren()?->count());
        self::assertEquals(0, $explain->getChild(1)?->getChildren()?->count());

        self::assertEquals(10.0, $explain->getChild(0)?->getScore());
        self::assertEquals(0.1, $explain->getChild(1)?->getScore());

        self::assertEquals('*:*^10.0', $explain->getAttribute(':query'));
    }

    /**
     * @test
     */
    public function testFixture003(): void
    {
        $explain = $this->getExplainFromFixture('3.4.003');

        self::assertEquals('P_164345', $explain->getDocumentId());
        self::assertEquals(100.48211, $explain->getRootNode()->getScore());

        self::assertEquals(2, $explain->getChildren()?->count());
        self::assertEquals(2, $explain->getChild(0)?->getChildren()?->count());
        self::assertEquals(3, $explain->getChild(1)?->getChildren()?->count());

        self::assertEquals(0.99999994, $explain->getChild(0)?->getScore());
        self::assertEquals(100.48212, $explain->getChild(1)?->getScore());
    }

    public function testFixture004(): void
    {
        $explain = $this->getExplainFromFixture('3.4.004');

        self::assertEquals('P_164345', $explain->getDocumentId());
        self::assertEquals(0.0, $explain->getRootNode()->getScore());
        self::assertEquals(3, $explain->getChildren()?->count());
        self::assertEquals(0, $explain->getChild(0)?->getChildren()?->count());
        self::assertEquals(0, $explain->getChild(1)?->getChildren()?->count());
        self::assertEquals(0, $explain->getChild(2)?->getChildren()?->count());

        self::assertEquals(3.8332133, $explain->getChild(1)?->getScore());
        self::assertEquals(32.0, $explain->getChild(2)?->getScore());
    }

    /**
     * @test
     */
    public function testFixture005(): void
    {
        $explain = $this->getExplainFromFixture('3.4.005');

        self::assertEquals('P_164345', $explain->getDocumentId());
        self::assertEquals(142.10316, $explain->getRootNode()->getScore());
        self::assertEquals(2, $explain->getChildren()?->count());
        self::assertEquals(2, $explain->getChild(0)?->getChildren()?->count());
        self::assertEquals(2, $explain->getChild(1)?->getChildren()?->count());

        self::assertEquals(71.05158, $explain->getChild(0)?->getScore());
        self::assertEquals(71.05158, $explain->getChild(1)?->getScore());
    }

    public function testFixture006(): void
    {
        $explain = $this->getExplainFromFixture('3.4.006');

        self::assertEquals('P_164345', $explain->getDocumentId());
        self::assertEquals(0.0, $explain->getRootNode()->getScore());
        self::assertEquals(2, $explain->getChildren()?->count());
        self::assertEquals(2, $explain->getChild(0)?->getChildren()?->count());
        self::assertEquals(0, $explain->getChild(1)?->getChildren()?->count());

        self::assertEquals(63.675232, $explain->getChild(0)?->getScore());
        self::assertEquals(0.0, $explain->getChild(1)?->getScore());
    }

    public function testFixture007(): void
    {
        $explain = $this->getExplainFromFixture('3.4.007');

        self::assertEquals('P_164345', $explain->getDocumentId());
        self::assertEquals(200.96422, $explain->getRootNode()->getScore());
        self::assertEquals(2, $explain->getChildren()?->count());
        self::assertEquals(2, $explain->getChild(0)?->getChildren()?->count());
        self::assertEquals(3, $explain->getChild(1)?->getChildren()?->count());
        self::assertEquals(0.99999994, $explain->getChild(0)?->getScore());
        self::assertEquals(200.96423, $explain->getChild(1)?->getScore());

        self::assertEquals('name:"Dell Widescreen"', $explain->getAttribute(':query'));
    }
}

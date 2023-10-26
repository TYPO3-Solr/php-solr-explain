<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Parser;
use ApacheSolrForTypo3\SolrExplain\Tests\AbstractSolrTest;

/**
 * This testcase should test if response from solr
 * can be parsed into an object structure.
 */
class ParserTestCase extends AbstractSolrTest
{
    /**
     * @test
     */
    public function testFixture001()
    {
        $content = $this->getFixtureContent('3.4.001.xml');
        $parser = new Parser();
        $result = $parser->parse($content);

        self::assertEquals(17, $result->getCompleteResultCount());
        self::assertEquals(9, $result->getQueryTime());
        self::assertEquals(10, $result->getResultCount());

        self::assertEquals(
            'GB18030TEST',
            $result->getDocumentCollection()->getDocument(0)->getFieldByName('id')->getValue()
        );

        self::assertEquals(
            ['electronics', 'hard drive'],
            $result->getDocumentCollection()->getDocument(1)->getFieldByName('cat')->getValue()
        );

        $expectedExplain = PHP_EOL . '1.0 = (MATCH) MatchAllDocsQuery, product of:' . PHP_EOL . '  1.0 = queryNorm' . PHP_EOL;
        $actualExplain = $result->getDocumentCollection()->getDocument(9)->getRawExplainData();
        self::assertEquals($expectedExplain, $actualExplain);
    }

    /**
     * @test
     */
    public function testFixture004()
    {
        $content = $this->getFixtureContent('3.4.004.xml');
        $parser = new Parser();
        $result = $parser->parse($content);

        self::assertEquals(2, $result->getQueryTime());
        self::assertEquals(10, $result->getResultCount());

        $expectedExplain4 	= PHP_EOL . '4.0 = (MATCH) MatchAllDocsQuery, product of:' . PHP_EOL . '  4.0 = queryNorm' . PHP_EOL;
        $actualExplain 		= $result->getDocumentCollection()->getDocument(3)->getRawExplainData();

        self::assertEquals($expectedExplain4, $actualExplain);
        self::assertEquals(2.0, $result->getTiming()->getTimeSpend());
        self::assertEquals(6, $result->getTiming()->getProcessingItems()->count());
    }

    /**
     * @test
     */
    public function testFixtureSolr4010()
    {
        $content = $this->getFixtureContent('4.0.010.xml');
        $parser = new Parser();
        $result = $parser->parse($content);

        self::assertEquals(3, $result->getResultCount());
        self::assertEquals(4, $result->getTiming()->getTimeSpend());
        self::assertEquals('LuceneQParser', $result->getQueryParser());
    }
}

<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Explanation;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\ExplainService;

/**
 * Test for the top level service class.
 */
class ExplainServiceTestCase extends AbstractExplanationTest
{
    /**
     * @test
     */
    public function testFixture1(): void
    {
        $content 	= $this->getFixtureContent('3.0.001.txt');
        $result 	= ExplainService::getFieldImpactsFromRawContent(
            $content,
            'foo',
            'bar'
        );

        self::assertEquals(['name' => 100], $result);
    }

    /**
 * @test
 */
    public function testCanNotCreateEmptyNodes(): void
    {
        $content 	= $this->getFixtureContent('6.2.001.txt');
        $result 	= ExplainService::getFieldImpactsFromRawContent(
            $content,
            'foo',
            'bar'
        );

        $expectedResult = [
            'keywords' => 3.9746099859253836,
            'title' =>  7.1126007378396707,
            'content' => 88.912788999915335,
        ];
        self::assertEquals($expectedResult, $result);
    }

    /**
     * @test
     */
    public function testCanParseSynonymNodes(): void
    {
        $content 	= $this->getFixtureContent('6.6.001.txt');
        $result 	= ExplainService::getFieldImpactsFromRawContent(
            $content,
            'foo',
            'bar'
        );

        $expectedResult = [
            'keywords' => 8.239458064841905,
            'description' => 2.1925963619964572,
            'tagsH1' => 8.539163146876982,
            'content' => 81.02878024976832,
        ];

        self::assertEquals($expectedResult, $result);
    }

    /**
     * @test
     */
    public function testCanParse82Response(): void
    {
        $content 	= $this->getFixtureContent('8.2.001.txt');
        $result 	= ExplainService::getFieldImpactsFromRawContent(
            $content,
            'foo',
            'bar'
        );

        $expectedResult = [
            'content' => 85.44380986095436,
            'tagsH2H3' => 4.056216176545581,
            'title' => 10.499972051284612,
        ];

        self::assertEquals($expectedResult, $result);
    }
}

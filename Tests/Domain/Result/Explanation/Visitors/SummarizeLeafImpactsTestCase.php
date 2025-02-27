<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Explanation\Visitors;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Content;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\ExplainResult;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\MetaData;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Parser;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Visitors\SummarizeLeafImpacts;
use ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Explanation\AbstractExplanationTest;

class SummarizeLeafImpactsTestCase extends AbstractExplanationTest
{
    protected function getExplain(string $filename): ExplainResult
    {
        $fileContent = $this->getFixtureContent($filename . '.txt');
        $content = new Content($fileContent);
        $metaData = new MetaData('P_164345', 'auto');
        $parser = new Parser();

        $parser->injectExplainResult(new ExplainResult());
        return $parser->parse($content, $metaData);
    }

    /**
     * @return array{array{
     *     fixtureName: string,
     *     expectedImpactSum?: float,
     * }}
     */
    public static function leafSumFixtureNameDataProvider(): array
    {
        return [
            ['fixtureName' => '3.0.001'],
            ['fixtureName' => '3.0.002'],
            ['fixtureName' => '3.0.003'],
            ['fixtureName' => '3.0.004'],
            ['fixtureName' => '3.0.005'],
            ['fixtureName' => '3.4.001'],
            ['fixtureName' => '3.4.002'],
            ['fixtureName' => '3.4.003'],
            ['fixtureName' => '3.4.004'],
            ['fixtureName' => '3.4.005'],
            ['fixtureName' => '3.4.006'],
            ['fixtureName' => '3.4.007'],
            ['fixtureName' => '3.4.008'],
            ['fixtureName' => '3.4.009'],
            ['fixtureName' => '3.4.010'],
            ['fixtureName' => '3.4.011'],
            ['fixtureName' => '3.4.012'],
            ['fixtureName' => '3.4.013'],
            ['fixtureName' => '3.4.014'],
            ['fixtureName' => '3.4.015'],
            ['fixtureName' => '3.4.016'],
            ['fixtureName' => '3.4.017'],
            //contains invalid content therefore no overall impact of 100 expected
            [
                'fixtureName' => '3.4.018',
                'expectedImpactSum' => 0.0,
            ],
            ['fixtureName' => '3.4.019'],
            ['fixtureName' => '3.4.020'],
            ['fixtureName' => '3.4.021'],
            ['fixtureName' => '3.4.022'],
            ['fixtureName' => '3.4.023'],
            ['fixtureName' => '3.4.024'],
            ['fixtureName' => '3.4.025'],
            ['fixtureName' => '3.4.026'],
            ['fixtureName' => '3.4.027'],
            ['fixtureName' => '3.4.028'],
            ['fixtureName' => '4.0.001'],
            ['fixtureName' => 'complex'],
            ['fixtureName' => 'custom.tieBreaker'],
        ];
    }

    /**
     * The sum for all leaf impacts in percentage should be 100%.
     *
     * This testcase is used to test this for some fixture files.
     *
     * @test
     * @dataProvider leafSumFixtureNameDataProvider
     */
    public function verifyFixtureLeafImpactSum(
        string $fixtureName,
        ?float $expectedImpactSum = 100.0,
    ): void {
        $explain = $this->getExplain($fixtureName);
        $visitor = new SummarizeLeafImpacts();
        $explain->getRootNode()->visitNodes($visitor);
        self::assertEquals($expectedImpactSum, $visitor->getSum());
    }
}

<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Explanation;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Content;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\ExplainResult;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\MetaData;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Parser;
use ApacheSolrForTypo3\SolrExplain\Tests\AbstractSolrTest;

abstract class AbstractExplanationTestCase extends AbstractSolrTest
{
    protected function getExplainFromFixture(string $filename): ExplainResult
    {
        $fileContent = $this->getFixtureContent($filename . '.txt');
        $content = new Content($fileContent);
        $metaData = new MetaData('P_164345', 'auto');

        $parser = new Parser();
        $parser->injectExplainResult(new ExplainResult());
        return $parser->parse($content, $metaData);
    }
}

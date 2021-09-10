<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests\Domain\Result\Explanation;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Content;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\ExplainResult;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\MetaData;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Parser;
use ApacheSolrForTypo3\SolrExplain\Tests\AbstractSolrTest;

abstract class AbstractExplanationTestCase extends AbstractSolrTest
{

	/**
	 * @param $filename
	 * @return ExplainResult
	 */
	protected function getExplainFromFixture($filename)
    {
		$fileContent = $this->getFixtureContent($filename.".txt");
		$content = new Content($fileContent);
		$metaData = new MetaData('P_164345','auto');

		$parser = new Parser();
		$parser->injectExplainResult(new ExplainResult());
		$explain = $parser->parse($content,$metaData);

		return $explain;
	}
}

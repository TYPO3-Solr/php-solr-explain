<?php

namespace Solr\Tests\Domain\Result\Explanation;

abstract class AbstractExplanationTestCase extends \Solr\Tests\AbstractSolrTest {

	/**
	 * @param $filename
	 * @return \Solr\Domain\Result\Explanation\Explain
	 */
	protected function getExplainFromFixture($filename) {
		$fileContent = $this->getFixtureContent($filename.".txt");
		$content = new \Solr\Domain\Result\Explanation\Content($fileContent);
		$metaData = new \Solr\Domain\Result\Explanation\MetaData('P_164345','auto');

		$parser = new \Solr\Domain\Result\Explanation\Parser();
		$parser->injectExplain(new \Solr\Domain\Result\Explanation\Explain());
		$explain = $parser->parse($content,$metaData);

		return $explain;
	}
}

?>
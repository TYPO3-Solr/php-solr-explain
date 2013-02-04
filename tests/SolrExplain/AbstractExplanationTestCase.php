<?php

namespace SolrExplain\Tests;

abstract class AbstractExplanationTestCase extends \PHPUnit_Framework_TestCase {
	/**
	 * Helper method to get the path of a "fixtures" folder that is relative to the current class folder.
	 *
	 * @return string
	 */
	protected function getTestFixturePath() {
		$reflector	= new \ReflectionClass(get_class($this));
		$path 		= dirname($reflector->getFileName()).'/Fixtures/';
		return $path;
	}

	/**
	 * Helper method to get a content of a fixture that is located in the "fixtures" folder beside the
	 * current testcase.
	 *
	 * @param $fixtureFilename
	 * @return string
	 */
	protected function getFixtureContent($fixtureFilename) {
		return file_get_contents($this->getTestFixturePath().$fixtureFilename);
	}


	/**
	 * @param $filename
	 * @return \SolrExplain\Domain\Explanation\Explain
	 */
	protected function getExplainFromFixture($filename) {
		$fileContent = $this->getFixtureContent($filename.".txt");
		$content = new \SolrExplain\Domain\Explanation\Content($fileContent);
		$metaData = new \SolrExplain\Domain\Explanation\MetaData('P_164345','auto');

		$parser = new \SolrExplain\Domain\Explanation\Parser();
		$parser->injectExplain(new \SolrExplain\Domain\Explanation\Explain());
		$explain = $parser->parse($content,$metaData);

		return $explain;
	}
}

?>
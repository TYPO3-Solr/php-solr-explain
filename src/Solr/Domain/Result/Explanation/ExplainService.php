<?php

namespace Solr\Domain\Result\Explanation;

/**
 * Top level service to build an explain object from the raw response.
 *
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
 */
class ExplainService {

	/**
	 * @param string $explainContent
	 * @param string $documentId
	 * @param string $mode
	 * @return \Solr\Domain\Result\Explanation\Explain
	 */
	public static function getExplainFromRawContent($explainContent, $documentId, $mode) {
		$content 	= new \Solr\Domain\Result\Explanation\Content($explainContent);
		$metaData 	= new \Solr\Domain\Result\Explanation\MetaData($documentId, $mode);
		$parser 	= new \Solr\Domain\Result\Explanation\Parser();
		$parser->injectExplain(new \Solr\Domain\Result\Explanation\Explain());

		return $parser->parse($content,$metaData);
	}

	/**
	 * @param string $explainContent
	 * @param string $documentId
	 * @param string $mode
	 * @return array
	 */
	public static function getFieldImpactsFromRawContent($explainContent, $documentId, $mode) {
		$explain 	= self::getExplainFromRawContent($explainContent, $documentId, $mode);
		$fieldImpactVisitor = new \Solr\Domain\Result\Explanation\Visitors\SummarizeFieldImpacts();
		$explain->getRootNode()->visitNodes($fieldImpactVisitor);

		return $fieldImpactVisitor->getFieldImpacts();
	}
}
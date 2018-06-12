<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Content;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\ExplainResult;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\MetaData;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Parser;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Visitors\SummarizeFieldImpacts;

/**
 * Top level service to build an explain object from the raw response.
 */
class ExplainService {

	/**
	 * @param string $explainContent
	 * @param string $documentId
	 * @param string $mode
	 * @return ExplainResult
	 */
	public static function getExplainFromRawContent($explainContent, $documentId, $mode) {
		$content 	= new Content($explainContent);
		$metaData 	= new MetaData($documentId, $mode);
		$parser 	= new Parser();
		$parser->injectExplainResult(new ExplainResult());

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
		$fieldImpactVisitor = new SummarizeFieldImpacts();
		$explain->getRootNode()->visitNodes($fieldImpactVisitor);

		return $fieldImpactVisitor->getFieldImpacts();
	}
}
<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Visitors\SummarizeFieldImpacts;

/**
 * Top level service to build an explain object from the raw response.
 */
class ExplainService
{
    /**
     * @param string $explainContent
     * @param string $documentId
     * @param string $mode
     * @return ExplainResult
     */
    public static function getExplainFromRawContent(string $explainContent, string $documentId, string $mode): ExplainResult
    {
        $content 	= new Content($explainContent);
        $metaData 	= new MetaData($documentId, $mode);
        $parser 	= new Parser();
        $parser->injectExplainResult(new ExplainResult());

        return $parser->parse($content, $metaData);
    }

    /**
     * @param string $explainContent
     * @param string $documentId
     * @param string $mode
     * @return float[]
     */
    public static function getFieldImpactsFromRawContent(string $explainContent, string $documentId, string $mode): array
    {
        $explain 	= self::getExplainFromRawContent($explainContent, $documentId, $mode);
        $fieldImpactVisitor = new SummarizeFieldImpacts();
        $explain->getRootNode()->visitNodes($fieldImpactVisitor);

        return $fieldImpactVisitor->getFieldImpacts();
    }
}

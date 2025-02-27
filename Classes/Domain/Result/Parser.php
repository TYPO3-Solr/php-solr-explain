<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Document\Collection;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Document\Document;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Document\Field\Field;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing\Item;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing\ItemCollection;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing\Timing;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use RuntimeException;

class Parser
{
    /**
     * @var DOMNodeList<DOMNode>|false|null
     */
    protected null|false|DOMNodeList $explainNodes = null;

    /**
     * @param string $xml
     * @return Result
     */
    public function parse(string $xml): Result
    {
        $result 	= new Result();
        $dom		= new DOMDocument('1.0', 'UTF-8');
        $dom->loadXML(trim($xml));

        $xpath 		= new DOMXPath($dom);

        $completeResultCount = $this->extractCompleteResultCount($xpath);
        $result->setCompleteResultCount($completeResultCount);

        $queryTime 	= $this->extractQueryTime($xpath);
        $result->setQueryTime($queryTime);

        $documentCollection = $this->extractDocumentCollection($xpath);
        $result->setDocumentCollection($documentCollection);

        $timing = $this->extractTiming($xpath);
        $result->setTiming($timing);

        $queryParserName = $this->extractParserName($xpath);
        $result->setQueryParser($queryParserName);

        return $result;
    }

    protected function extractCompleteResultCount(DOMXPath $resultXpath): int
    {
        $result 	= 0;
        $numFound 	= $resultXpath->query('//response/result/@numFound');

        if (
            $numFound instanceof DOMNodeList
            && isset($numFound->item(0)->textContent)
        ) {
            $result = (int)$numFound->item(0)->textContent;
        }

        return $result;
    }

    protected function extractQueryTime(DOMXPath $resultXpath): int
    {
        $result 		= 0;
        $responseTime 	= $resultXpath->query("//lst[@name='responseHeader']/int[@name='QTime']");

        if (
            $responseTime instanceof DOMNodeList
            && isset($responseTime->item(0)->textContent)
        ) {
            $result = (int)$responseTime->item(0)->textContent;
        }

        return $result;
    }

    /**
     * @return Collection<int, Document>
     */
    protected function extractDocumentCollection(DOMXPath $resultXpath): Collection
    {
        $result 		= new Collection();
        $documentNodes 	= $resultXpath->query('//doc');
        if (!$documentNodes instanceof DOMNodeList) {
            return $result;
        }
        $documentCount 	= 0;

        foreach ($documentNodes as $documentNode) {
            $document = new Document();

            foreach ($documentNode->childNodes as $fieldNode) {
                if (!($fieldNode instanceof DOMElement)) {
                    continue;
                }
                $this->extractDocumentFields($fieldNode, $document);
            }

            $explainContent = $this->extractExplainContent($resultXpath, $documentCount);
            $document->setRawExplainData($explainContent);

            $result->append($document);
            $documentCount++;
        }

        return $result;
    }

    /**
     * This method is used to extract the fields from the xml response
     * and attach them to the document object.
     */
    protected function extractDocumentFields(DOMElement $fieldNode, Document $document): void
    {
        $field = new Field();

        if ($fieldNode->nodeName == 'arr') {
            //multivalue field
            $value = [];
            foreach ($fieldNode->childNodes as $singleField) {
                $value[] = $singleField->textContent;
            }
        } else {
            //single value field
            $value = $fieldNode->textContent;
        }

        $field->setValue($value);

        $fieldName = $fieldNode->getAttribute('name');
        $field->setName($fieldName);

        $document->addField($field);
    }

    /**
     * @return DOMNodeList<DOMNode>|false a DOMNodeList containing all nodes matching
     * the given XPath expression. Any expression which does not return nodes
     * will return an empty DOMNodeList. The return is false if the expression
     * is malformed or the context-node is invalid.
     */
    protected function getExplainNodes(DOMXPath $resultXPath): DOMNodeList|false
    {
        if ($this->explainNodes == null) {
            $this->explainNodes = $resultXPath->query("//lst[@name='debug']/lst[@name='explain']/str");
        }

        return $this->explainNodes;
    }

    protected function extractExplainContent(DOMXPath $resultXPath, int $documentCount): string
    {
        $explainContent = '';

        $explainNodes 	= $this->getExplainNodes($resultXPath);

        if (
            $explainNodes instanceof DOMNodeList
            && isset($explainNodes->item($documentCount)->textContent)
        ) {
            $explainContent = $explainNodes->item($documentCount)->textContent;
        }

        return $explainContent;
    }

    protected function extractTiming(DOMXPath $xpath): Timing
    {
        $prepareItemCollection 		= new ItemCollection();
        $processingItemCollection	= new ItemCollection();

        $path 			= "//lst[@name='debug']/lst[@name='timing']/*[@name='time']";
        $overallTime 	= $this->getTimeFromNode($xpath, $path);

        //get prepare timing and items
        $path 			= "//lst[@name='debug']/lst[@name='timing']/lst[@name='prepare']/*[@name='time']";
        $prepareTime	= $this->getTimeFromNode($xpath, $path);
        $prepareItemCollection->setTimeSpend($prepareTime);

        $prepareNodesPath = "//lst[@name='debug']/lst[@name='timing']/lst[@name='prepare']/lst";
        $this->extractTimingSubNodes($xpath, $prepareNodesPath, $prepareItemCollection);

        //get processing time and items
        $path 			= "//lst[@name='debug']/lst[@name='timing']/lst[@name='process']/*[@name='time']";
        $processingTime	= $this->getTimeFromNode($xpath, $path);
        $processingItemCollection->setTimeSpend($processingTime);

        $processingNodesPath = "//lst[@name='debug']/lst[@name='timing']/lst[@name='process']/lst";
        $this->extractTimingSubNodes($xpath, $processingNodesPath, $processingItemCollection);

        //build all and return
        $result = new Timing($prepareItemCollection, $processingItemCollection);
        $result->setTimeSpend($overallTime);

        return $result;
    }

    /**
     * This method is used to build timing items from timing sub-nodes.
     */
    protected function extractTimingSubNodes(DOMXPath $xpath, string $nodeXPath, ItemCollection $itemCollection): void
    {
        $nodes = $xpath->query($nodeXPath);
        if (!($nodes instanceof DOMNodeList)) {
            throw new RuntimeException(
                'Failed to extract timing sub-nodes for: ' . $nodeXPath,
            );
        }
        foreach ($nodes as $node) {
            /** @var DOMElement $node */
            $name = $node->getAttribute('name');
            $time = 0.0;
            if (isset($node->childNodes->item(0)->textContent)) {
                $time = (float)$node->childNodes->item(0)->textContent;
            }

            $item = new Item();
            $item->setComponentName($name);
            $item->setTimeSpend($time);

            $itemCollection->append($item);
        }
    }

    protected function getTimeFromNode(DOMXPath $xpath, string $path): float
    {
        $timeNode = $xpath->query($path);
        $time = 0.0;
        if (
            $timeNode instanceof DOMNodeList
            && isset($timeNode->item(0)->textContent)
        ) {
            $time = (float)$timeNode->item(0)->textContent;
        }

        return $time;
    }

    protected function extractParserName(DOMXPath $xpath): string
    {
        $result				= '';
        $path 				= "//lst[@name='debug']/str[@name='QParser']";
        $queryParserNode 	= $xpath->query($path);

        if (
            $queryParserNode instanceof DOMNodeList
            && isset($queryParserNode->item(0)->textContent)
        ) {
            $result = $queryParserNode->item(0)->textContent;
        }

        return $result;
    }
}

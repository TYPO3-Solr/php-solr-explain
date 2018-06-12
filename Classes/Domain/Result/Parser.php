<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Document\Collection;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Document\Document;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Document\Field\Field;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Result;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing\Item;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing\ItemCollection;
use ApacheSolrForTypo3\SolrExplain\Domain\Result\Timing\Timing;

class Parser {

	/**
	 * @var
	 */
	protected $explainNodes;

	/**
	 * @param $xml
	 * @return Result
	 */
	public function parse($xml) {
		$result 	= new Result();
		$dom		= new \DOMDocument(1.0,"UTF-8");
		$dom->loadXML(trim($xml));

		$xpath 		= new \DOMXPath($dom);

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

	/**
	 * @param \DOMXPath $resultXpath
	 * @return int
	 */
	protected function extractCompleteResultCount(\DOMXPath $resultXpath) {
		$result 	= 0;
		$numFound 	= $resultXpath->query("//response/result/@numFound");

		if(isset($numFound->item(0)->textContent)) {
			$result = (int) $numFound->item(0)->textContent;
		}

		return $result;
	}

	/**
	 * @param \DOMXPath $resultXpath
	 * @return int
	 */
	protected function extractQueryTime(\DOMXPath $resultXpath) {
		$result 		= 0;
		$responseTime 	= $resultXpath->query("//lst[@name='responseHeader']/int[@name='QTime']");

		if(isset($responseTime->item(0)->textContent)) {
			$result = (int) $responseTime->item(0)->textContent;
		}

		return $result;
	}

	/**
	 * @param \DOMXPath $resultXpath
	 * @return Collection
	 */
	protected function extractDocumentCollection(\DOMXPath $resultXpath) {
		$result 		= new Collection();
		$documentNodes 	= $resultXpath->query("//doc");
		$documentCount 	= 0;

		foreach($documentNodes as $documentNode) {
			$document = new Document();

			/** @var $documentNode \DOMElement */
			foreach($documentNode->childNodes as $fieldNode) {
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
	 *
	 * @param $fieldNode
	 * @param Document $document
	 */
	protected function extractDocumentFields($fieldNode, Document $document) {
		if ($fieldNode instanceof \DOMElement) {
			$field = new Field();

			if ($fieldNode->nodeName == 'arr') {
				//multivalue field
				$value = array();
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
	}

	/**
	 * @param \DOMXPath $resultXPath
	 * @return mixed
	 */
	protected function getExplainNodes(\DOMXPath $resultXPath) {
		if($this->explainNodes == null) {
			$this->explainNodes = $resultXPath->query("//lst[@name='debug']/lst[@name='explain']/str");
		}

		return $this->explainNodes;
	}

	/**
	 * @param \DOMXPath $resultXPath
	 * @param
	 */
	protected function extractExplainContent(\DOMXPath $resultXPath, $documentCount) {
		$explainContent = '';

		$explainNodes 	= $this->getExplainNodes($resultXPath);

		if(isset($explainNodes->item($documentCount)->textContent)) {
			$explainContent = $explainNodes->item($documentCount)->textContent;
		}

		return $explainContent;
	}

	/**
	 * @param \DOMXPath $xpath
	 * @return Timing
	 */
	protected function extractTiming(\DOMXPath $xpath) {
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
	 * This method is used to build timing items from timing subnodes.
	 *
	 * @param \DOMXPath $xpath
	 * @param string $nodeXPath
	 * @param ItemCollection $itemCollection
	 */
	protected function extractTimingSubNodes(\DOMXPath $xpath, $nodeXPath, $itemCollection){
		$nodes = $xpath->query($nodeXPath);

		foreach ($nodes as $node) {
			/** @var $node \DOMElement */
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

	/**
	 * @param \DOMXPath $xpath
	 * @param string $path
	 * @return float
	 */
	protected function getTimeFromNode($xpath, $path){
		$timeNode = $xpath->query($path);
		$time = 0.0;
		if (isset($timeNode->item(0)->textContent)) {
			$time = (float)$timeNode->item(0)->textContent;
		}

		return $time;
	}

	/**
	 * @param \DOMXPath $xpath
	 */
	protected function extractParserName(\DOMXPath $xpath) {
		$result				= '';
		$path 				= "//lst[@name='debug']/str[@name='QParser']";
		$queryParserNode 	= $xpath->query($path);

		if(isset($queryParserNode->item(0)->textContent)) {
			$result = (string) $queryParserNode->item(0)->textContent;
		}

		return $result;
	}
}

?>
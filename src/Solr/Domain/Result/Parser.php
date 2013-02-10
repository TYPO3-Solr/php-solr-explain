<?php

namespace Solr\Domain\Result;

/**
 *
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
 */
class Parser {

	/**
	 * @var
	 */
	protected $explainNodes;

	/**
	 * @param $xml
	 * @return \Solr\Domain\Result\Result
	 */
	public function parse($xml) {
		$result 	= new \Solr\Domain\Result\Result();
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
	 * @return \Solr\Domain\Result\Document\Collection
	 */
	protected function extractDocumentCollection(\DOMXPath $resultXpath) {
		$result 		= new \Solr\Domain\Result\Document\Collection();
		$documentNodes 	= $resultXpath->query("//doc");
		$documentCount 	= 0;

		foreach($documentNodes as $documentNode) {
			$document = new \Solr\Domain\Result\Document\Document();

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
	 * @param \Solr\Domain\Result\Document\Document $document
	 */
	protected function extractDocumentFields($fieldNode,
											 \Solr\Domain\Result\Document\Document $document) {
		if ($fieldNode instanceof \DOMElement) {
			$field = new \Solr\Domain\Result\Document\Field\Field();

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
	 * @param $resultXPath
	 * @return mixed
	 */
	protected function getExplainNodes($resultXPath) {
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
	 * @return Timing\Timing
	 */
	protected function extractTiming(\DOMXPath $xpath) {
		$prepareItemCollection 		= new \Solr\Domain\Result\Timing\ItemCollection();
		$processingItemCollection	= new \Solr\Domain\Result\Timing\ItemCollection();

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
		$result = new \Solr\Domain\Result\Timing\Timing($prepareItemCollection, $processingItemCollection);
		$result->setTimeSpend($overallTime);

		return $result;
	}

	/**
	 * This method is used to build timing items from timing subnodes.
	 *
	 * @param $xpath
	 * @param $nodeXPath
	 * @param $itemCollection
	 */
	protected function extractTimingSubNodes($xpath, $nodeXPath, $itemCollection){
		$nodes = $xpath->query($nodeXPath);

		foreach ($nodes as $node) {
			/** @var $node \DOMElement */
			$name = $node->getAttribute('name');
			$time = 0.0;
			if (isset($node->childNodes->item(0)->textContent)) {
				$time = (float)$node->childNodes->item(0)->textContent;
			}

			$item = new \Solr\Domain\Result\Timing\Item();
			$item->setComponentName($name);
			$item->setTimeSpend($time);

			$itemCollection->append($item);
		}
	}

	/**
	 * @param $xpath
	 * @param $path
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
}

?>
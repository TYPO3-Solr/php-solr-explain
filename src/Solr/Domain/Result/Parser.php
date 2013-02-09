<?php

namespace Solr\Domain\Result;

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
}

?>
<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Document;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Document\Document;

class Collection extends \ArrayObject {

	/**
	 * @param $offset
	 * @return Document
	 */
	public function getDocument($offset) {
		return $this->offsetGet($offset);
	}
}

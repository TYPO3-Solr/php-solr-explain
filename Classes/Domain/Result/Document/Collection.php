<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Document;

use ArrayObject;

class Collection extends ArrayObject
{

	/**
	 * @param $offset
	 * @return Document
	 */
	public function getDocument($offset): Document
    {
		return $this->offsetGet($offset);
	}
}

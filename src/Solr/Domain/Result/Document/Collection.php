<?php

namespace Solr\Domain\Result\Document;

class Collection extends \ArrayObject {

	/**
	 * @param $offset
	 * @return \Solr\Domain\Result\Document\Document
	 */
	public function getDocument($offset) {
		return $this->offsetGet($offset);
	}
}

?>
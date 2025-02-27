<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Document;

use ArrayObject;

/**
 * @template-extends ArrayObject<int, Document>
 */
class Collection extends ArrayObject
{
    public function getDocument(int $offset): ?Document
    {
        return $this->offsetGet($offset);
    }
}

<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation;

/**
 * Metadata object. Used during parsing and contains some meta-data
 * like the corresponding document id or the parsing mode.
 */
class MetaData
{
    protected string $documentId = '';

    protected string $mode = '';

    public function __construct(string $documentId, string $mode)
    {
        $this->setDocumentId($documentId);
        $this->setMode($mode);
    }

    public function setDocumentId(string $documentId): void
    {
        $this->documentId = $documentId;
    }

    public function getDocumentId(): string
    {
        return $this->documentId;
    }

    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }

    /**
     * @noinspection PhpUnused
     */
    public function getMode(): string
    {
        return $this->mode;
    }
}

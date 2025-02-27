<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Nodes\Explain;
use ArrayObject;

/**
 * Root object of the parse explain.
 *
 * Can be used to travers the explain result.
 *
 * Eg:
 *
 * $explain->getChild(0)->getScore()
 */
class ExplainResult
{
    /**
     * ID of the relevant document.
     */
    protected string $documentId = '';

    /**
     * @var ?ArrayObject<int, Explain>
     */
    protected ?ArrayObject $children = null;

    protected Explain $rootNode;

    /**
     * @var ArrayObject<string, string>
     */
    protected ArrayObject $attributes;

    public function __construct()
    {
        $this->attributes = new ArrayObject();
    }

    /**
     * @param ?ArrayObject<int, Explain> $children
     */
    public function setChildren(?ArrayObject $children): void
    {
        $this->children = $children;
    }

    /**
     * @return ?ArrayObject<int, Explain>
     */
    public function getChildren(): ?ArrayObject
    {
        return $this->children;
    }

    public function getChild(int $index): ?Explain
    {
        return $this->children[$index] ?? null;
    }

    public function setRootNode(Explain $rootNode): void
    {
        $this->rootNode = $rootNode;
    }

    /**
     * Method to retrieve the root node of the explain object.
     */
    public function getRootNode(): Explain
    {
        return $this->rootNode;
    }

    /**
     * Method to set the corresponding document id.
     *
     * @param string $documentId
     */
    public function setDocumentId(string $documentId): void
    {
        $this->documentId = $documentId;
    }

    /**
     * Document id where this explanation belongs to.
     *
     * @return string
     */
    public function getDocumentId(): string
    {
        return $this->documentId;
    }

    /**
     * Method to set a single attribute
     *
     * Eg: :query
     */
    public function setAttribute(string $key, string $value): void
    {
        $this->attributes->offsetSet($key, $value);
    }

    /**
     * Return the value for a single attribute.
     */
    public function getAttribute(string $key): mixed
    {
        return $this->attributes->offsetGet($key);
    }
}

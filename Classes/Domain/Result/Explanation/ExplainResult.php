<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Nodes\Explain;
use ArrayObject;
use Solr\Domain\Result\Explanation\ExplainNode;

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
     * Id of the relevant document.
     *
     * @var string
     */
    protected $documentId = '';

    /**
     * @var ArrayObject
     */
    protected $children;

    /**
     * @var Explain
     */
    protected $rootNode;

    /**
     * @var ArrayObject
     */
    protected $attributes;

    public function __construct()
    {
        $this->attributes = new ArrayObject();
    }

    /**
     * @param ArrayObject $children
     */
    public function setChildren(ArrayObject $children)
    {
        $this->children = $children;
    }

    /**
     * @return ArrayObject<ExplainNode>
     */
    public function getChildren(): ?ArrayObject
    {
        return $this->children;
    }

    /**
     * @param $index
     * @return Explain
     */
    public function getChild($index): Explain
    {
        return $this->children[$index];
    }

    /**
     * @param Explain $rootNode
     */
    public function setRootNode(Explain $rootNode)
    {
        $this->rootNode = $rootNode;
    }

    /**
     * Method to retrieve the root node of the explain.
     *
     * @return Explain
     */
    public function getRootNode(): ?Explain
    {
        return $this->rootNode;
    }

    /**
     * Method to set the corresponding document id.
     *
     * @param string $documentId
     */
    public function setDocumentId(string $documentId)
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
     *
     * @param string $key
     * @param mixed $value
     */
    public function setAttribute(string $key, $value)
    {
        $this->attributes->offsetSet($key, $value);
    }

    /**
     * Return the value for a single attribute.
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute(string $key)
    {
        return $this->attributes->offsetGet($key);
    }
}

<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation;

/**
 * Represent the raw content of a solr explanation:.
 *
 * Eg:
 * 0.8621642 = (MATCH) sum of:
 *   0.8621642 = (MATCH) sum of:
 *     0.4310821 = (MATCH) max of:
 *       0.4310821 = (MATCH) weight(name:hard in 1), product of:
 *         0.5044475 = queryWeight(name:hard), product of:
 *           2.734601 = idf(docFreq=2, maxDocs=17)
 *           0.18446842 = queryNorm
 *         0.8545628 = (MATCH) fieldWeight(name:hard in 1), product of:
 *           1.0 = tf(termFreq(name:hard)=1)
 *           2.734601 = idf(docFreq=2, maxDocs=17)
 *           0.3125 = fieldNorm(field=name, doc=1)
 * ....
 */
class Content
{

	/**
	 * @var string
	 */
	protected $content;

	/**
	 * @param string $content
	 */
	public function __construct(string $content)
    {
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function getContent(): string
    {
		return $this->content;
	}
}

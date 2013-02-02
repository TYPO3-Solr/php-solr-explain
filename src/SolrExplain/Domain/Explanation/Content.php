<?php

namespace SolrExplain\Domain\Explanation;

/**
 * Represent the content of an solr explanation.
 *
 * Eg:
 */
class Content {

	/**
	 * @var string
	 */
	protected $content;

	/**
	 * @param string $content
	 */
	public function __construct($content) {
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}
}
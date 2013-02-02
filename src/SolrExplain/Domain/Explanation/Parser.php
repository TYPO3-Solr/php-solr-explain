<?php

namespace SolrExplain\Domain\Explanation;

/**
 * Parser used to parse the explain content into a node structure.
 *
 * @author Timo Schmidt <timo-schmidt@gmx.net>
 */
class Parser {

	/**
	 * @var Explain
	 */
	protected $explain;

	/**
	 * @param \SolrExplain\Domain\Explanation\Explain $explain
	 */
	public function injectExplain(\SolrExplain\Domain\Explanation\Explain $explain) {
		$this->explain = $explain;
	}

	/**
	 * @param string
	 * @return  \SolrExplain\Domain\Explanation\ExplainNode
	 */
	protected function getRootNode($content) {
		$tokens = new \ArrayObject();
		$this->parseChildNodes($content,$tokens);
		return $tokens[0];
	}

	/**
	 * Recursive method to parse the explain content into a child node structure.
	 *
	 * @param $contextContent
	 * @param \ArrayObject $collection
	 * @return \ArrayObject
	 */
	protected function parseChildNodes($contextContent, \ArrayObject $collection, $parent = null) {
		$matches 	= array();

		//look for tokens stating with 0-9* and get all following lines stating with " " space
		preg_match_all('~(?P<token>^[0-9][^\n]*\n(([ ][^\n]*\n)*)?)~m',$contextContent,$matches);

		if( array_key_exists('token',$matches)) {
			foreach($matches['token'] as $tokenKey => $tokenContent) {
				$tokenParts		= explode(PHP_EOL,$tokenContent);
				$tokenName		= trim(array_shift($tokenParts));

				$token 			= new \SolrExplain\Domain\Explanation\ExplainNode();

				$score = 0.0;
				$scoreMatches 	= array();
				preg_match('~(?<score>[0-9]*\.[0-9]*)~',$tokenName,$scoreMatches);
				if(isset($scoreMatches['score']) && (float) $scoreMatches['score'] > 0) {
					$score = (float) $scoreMatches['score'];
				}

				$token->setContent($tokenName);
				$token->setParent($parent);
				$token->setScore($score);

				$collection->append($token);

				$nextLevelContent = '';
				if(count($tokenParts)) {
					$preparedTokens = preg_replace('~^  ~ims','',$tokenParts);
					$nextLevelContent = implode(PHP_EOL,$preparedTokens);
				}

				if(trim($nextLevelContent) != '') {
					$this->parseChildNodes($nextLevelContent,$token->getChildren(),$token);
				}
			}
		}

		$collection;
	}

	/**
	 * Parses the explain content to an explain object wit child nodes.
	 *
	 * @return \SolrExplain\Domain\Explanation\Explain
	 */
	public function parse(\SolrExplain\Domain\Explanation\Content $content) {
		$rootNode = $this->getRootNode($content->getContent());
		$children = $rootNode->getChildren();
		$this->explain->setChildren($children);
		$this->explain->setRootNode($rootNode);

		return $this->explain;
	}
}
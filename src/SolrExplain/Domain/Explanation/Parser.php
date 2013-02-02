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
	 * @param string $tokenName
	 * @param int $level
	 */
	protected function getNodeType($tokenName, $level) {
		if(mb_strpos($tokenName,'sum of:') !== false) {
			return \SolrExplain\Domain\Explanation\ExplainNode::NODE_TYPE_SUM;
		}

		if(mb_strpos($tokenName,'product of:') !== false) {
			return \SolrExplain\Domain\Explanation\ExplainNode::NODE_TYPE_PRODUCT;
		}

		if(mb_strpos($tokenName,'max of:') !== false) {
			return \SolrExplain\Domain\Explanation\ExplainNode::NODE_TYPE_MAX;
		}

			//when nothing else matched we have a leaf node
		return \SolrExplain\Domain\Explanation\ExplainNode::NODE_TYPE_LEAF;
	}

	/**
	 * Recursive method to parse the explain content into a child node structure.
	 *
	 * @param $contextContent
	 * @param \ArrayObject $collection
	 * @return \ArrayObject
	 */
	protected function parseChildNodes($contextContent, \ArrayObject $collection, $parent = null,$level=0) {
		$matches 	= array();

		//look for tokens stating with 0-9* and get all following lines stating with " " space
		preg_match_all('~(?P<token>^[0-9][^\n]*\n(([ ][^\n]*\n)*)?)~m',$contextContent,$matches);

		if( array_key_exists('token',$matches)) {
			foreach($matches['token'] as $tokenKey => $tokenContent) {
				$tokenParts		= explode(PHP_EOL,$tokenContent);
				$tokenName		= trim(array_shift($tokenParts));

				$token 	= new \SolrExplain\Domain\Explanation\ExplainNode();
				$score 	= $this->getScoreFromTokenName($tokenName);
				$token->setContent($tokenName);
				$token->setParent($parent);
				$token->setScore($score);
				$token->setLevel($level);

				$nodeType = $this->getNodeType($tokenName,$level);
				$token->setNodeType($nodeType);

				$collection->append($token);

				$nextLevelContent = '';
				if(count($tokenParts)) {
					$preparedTokens = preg_replace('~^  ~ims','',$tokenParts);
					$nextLevelContent = implode(PHP_EOL,$preparedTokens);
				}

				if(trim($nextLevelContent) != '') {
					$level++;
					$this->parseChildNodes($nextLevelContent,$token->getChildren(),$token,$level);
				}
			}
		}

		$collection;
	}

	/**
	 * Extracts the score from a token name.
	 *
	 * Input eg: 3.8332133 = idf(docFreq=0, maxDocs=17)
	 * Output eg: 3.8332133
	 *
	 * @param string $tokenName
	 * @return float
	 */
	protected function getScoreFromTokenName($tokenName) {
		$score = 0.0;
		$scoreMatches 	= array();
		preg_match('~(?<score>[0-9]*\.[0-9]*)~',$tokenName,$scoreMatches);
		if(isset($scoreMatches['score']) && (float) $scoreMatches['score'] > 0) {
			$score = (float) $scoreMatches['score'];
		}

		return $score;
	}

	/**
	 * @param $content
	 * @return string
	 */
	protected function getQueryAttribute($content) {
		$querystring = '';
		$matches = array();
		preg_match("~^#(?<attributes>[^\n]*)~ism", $content, $matches);

		if(isset($matches['attributes'])) {
			$attributes 		= $matches['attributes'];
			$attributeMatches 	= array();
			preg_match('~.*q=(?<querystring>[^&]*)~ism',$attributes,$attributeMatches);
			if(isset($attributeMatches['querystring'])) {
				$querystring = $attributeMatches['querystring'];
					//convert boostvalues without decimals to boost value with
					//decimal eg: foo^20 => foo^20.0
				$querystring = preg_replace('~\^([0-9^.]+)~i','^$1.0',$querystring);
			}
		}

		return $querystring;
	}

	/**
	 * Parses the explain content to an explain object wit child nodes.
	 *
	 * @return \SolrExplain\Domain\Explanation\Explain
	 */
	public function parse(	\SolrExplain\Domain\Explanation\Content $content,
							\SolrExplain\DOmain\Explanation\MetaData $metaData) {

		$rawContent = $content->getContent();
		$rootNode = $this->getRootNode($rawContent);
		$this->explain->setRootNode($rootNode);

		$children = $rootNode->getChildren();
		$this->explain->setChildren($children);

		$this->explain->setDocumentId($metaData->getDocumentId());
		$this->explain->setAttribute(':query',$this->getQueryAttribute($rawContent));

		return $this->explain;
	}
}
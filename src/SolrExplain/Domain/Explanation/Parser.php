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
	protected function getNodeFromName($tokenName) {
		if(mb_strpos($tokenName,'sum of:') !== false || mb_strpos($tokenName,'result of:') !== false)  {
			return new \SolrExplain\Domain\Explanation\Nodes\Sum();
		}

		if(mb_strpos($tokenName,'product of:') !== false) {
			return new \SolrExplain\Domain\Explanation\Nodes\Product();
		}

		if(mb_strpos($tokenName,'max') !== false && mb_strpos($tokenName,'of:')) {
			return new \SolrExplain\Domain\Explanation\Nodes\Max();
		}

			//when nothing else matched we have a leaf node
		return new \SolrExplain\Domain\Explanation\Nodes\Leaf();
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

				$node	= $this->getNodeFromName($tokenName);
				$score 	= $this->getScoreFromNodeName($tokenName);
				$node->setContent($tokenName);
				$node->setParent($parent);
				$node->setScore($score);
				$node->setLevel($level);

				$nodeFieldName = $this->getFieldNameFromNodeName($tokenName);
				$node->setFieldName($nodeFieldName);

				$collection->append($node);

				$nextLevelContent = '';
				if(count($tokenParts)) {
					$preparedTokens = preg_replace('~^  ~ims','',$tokenParts);
					$nextLevelContent = implode(PHP_EOL,$preparedTokens);
				}

				if(trim($nextLevelContent) != '') {
					$level++;
					$this->parseChildNodes($nextLevelContent,$node->getChildren(),$node,$level);
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
	 * @param string $nodeName
	 * @return float
	 */
	protected function getScoreFromNodeName($nodeName) {
		$score = 0.0;
		$scoreMatches 	= array();
		preg_match('~(?<score>[0-9]*\.[^ ]*)~',$nodeName,$scoreMatches);
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
	 * @param $nodeName
	 */
	protected function getFieldNameFromNodeName($nodeName) {
		$result 	= '';
		$matches 	= array();

		if(mb_strpos($nodeName,'weight(') !== false ){
			preg_match('~weight\((?<fieldname>[^\):]*)~', $nodeName, $matches);
		} elseif (mb_strpos($nodeName, 'queryWeight(') !== false ) {
			preg_match('~queryWeight\((?<fieldname>[^\):]*)~', $nodeName, $matches);
		} elseif (mb_strpos($nodeName,'fieldWeight(') !== false ) {
			preg_match('~fieldWeight\((?<fieldname>[^\):]*)~', $nodeName, $matches);
		} elseif (mb_strpos($nodeName, 'FunctionQuery(') !== false ) {
			preg_match('~FunctionQuery\([^\(]*\((?<fieldname>[^\):]*)~', $nodeName, $matches);

				//check if it is a nested function query an get inner fieldname
			$lastBracketPos = mb_strpos($matches['fieldname'],'(');
			if($lastBracketPos !== false) {
				$matches['fieldname'] = mb_substr($matches['fieldname']+1,$lastBracketPos);
			}
		}

		if(isset($matches['fieldname'])) {
			$result = $matches['fieldname'];
		}

		return $result;
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
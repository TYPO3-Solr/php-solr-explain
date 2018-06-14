<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Visitors;

use ApacheSolrForTypo3\SolrExplain\Domain\Result\Explanation\Nodes\Explain;

/**
 * This visitor is used to determine a percetage impacts
 * by a fieldname.
 */
class SummarizeFieldImpacts implements ExplainNodeVisitorInterface {

	/**
	 * @var float
	 */
	protected $sums = array();

	/**
	 * @param Explain $node
	 * @return mixed|void
	 */
	public function visit(Explain $node) {
		if($node->getNodeType() == $node::NODE_TYPE_LEAF) {
			if($node->getParent() != null) {
				$fieldName = $node->getParent()->getFieldName();

                if(trim($fieldName) === '') {
                    return;
                }

				if(!isset($this->sums[$fieldName])) {
					$this->sums[$fieldName] = 0;
				}

				$this->sums[$fieldName] += $node->getAbsoluteImpactPercentage();

			}
		}
	}

	/**
	 * Returns the fieldnames that have been relevant during the explain.
	 *
	 * @return array<string>
	 */
	public function getRelevantFieldNames() {
		return array_keys($this->sums);
	}

	/**
	 * Returns the impact for a certain field by name.
	 *
	 * @param $fieldName
	 * @return float
	 */
	public function getFieldImpact($fieldName) {
		if(!array_key_exists($fieldName,$this->sums)) {
			return 0.0;
		}

		return $this->sums[$fieldName];
	}

	/**
	 * Returns the fieldname => impact array.
	 *
	 * @return array|float
	 */
	public function getFieldImpacts() {
		return $this->sums;
	}
}
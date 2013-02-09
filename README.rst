PHP Solr Explain
================

:Author: Timo Schmidt <timo.schmidt@aoemedia.de>
:Thx to: Michael Klapper <michael.klapper@aoemedia.de>
:Thx to: Solr.pl, Marek Rogoziński, Rafał Kuć
:Description: PHP Library to parse solr explain responses with php
:Build status: |buildStatusIcon|

The easiest way to use the explain php library is using composer:

1. Add the following include to your composer.json:

::

	"ts/solr-explain-php": "*"

Simple example:

::

	{
		"name": "explain test",
		"description": "Explain test application",
		"license": "MIT",
		"require": {
			"php": ">=5.3.3",
					"ts/solr-explain-php": "*"
		},
		"minimum-stability": "dev"
	}

2. Now install composer

::

	curl -s http://getcomposer.org/installer | php

3. Now compose you yaml file:

::

	php composer.phar install

4. Use the services and object model in you app
(Note that the intention in the content is important):

::

	require_once 'vendor/autoload.php';
	$content = 	'0.8621642 = (MATCH) sum of:'.PHP_EOL.
			'  0.8621642 = (MATCH) sum of:'.PHP_EOL.
			'    0.4310821 = (MATCH) max of:'.PHP_EOL.
			'      0.4310821 = (MATCH) weight(name:hard in 1), product of:'.PHP_EOL.
			'        0.5044475 = queryWeight(name:hard), product of:'.PHP_EOL.
			'          2.734601 = idf(docFreq=2, maxDocs=17)'.PHP_EOL.
			'          0.18446842 = queryNorm'.PHP_EOL.
			'        0.8545628 = (MATCH) fieldWeight(name:hard in 1), product of:'.PHP_EOL.
			'          1.0 = tf(termFreq(name:hard)=1)'.PHP_EOL.
			'          2.734601 = idf(docFreq=2, maxDocs=17)'.PHP_EOL.
			'          0.3125 = fieldNorm(field=name, doc=1)'.PHP_EOL.
			'    0.4310821 = (MATCH) max of:'.PHP_EOL.
			'      0.4310821 = (MATCH) weight(name:drive in 1), product of:'.PHP_EOL.
			'        0.5044475 = queryWeight(name:drive), product of:'.PHP_EOL.
			'          2.734601 = idf(docFreq=2, maxDocs=17)'.PHP_EOL.
			'          0.18446842 = queryNorm'.PHP_EOL.
			'        0.8545628 = (MATCH) fieldWeight(name:drive in 1), product of:'.PHP_EOL.
			'          1.0 = tf(termFreq(name:drive)=1)'.PHP_EOL.
			'          2.734601 = idf(docFreq=2, maxDocs=17)'.PHP_EOL.
			'          0.3125 = fieldNorm(field=name, doc=1)'.PHP_EOL;

	$result 	= \Solr\Domain\Result\Explanation\ExplainService::getFieldImpactsFromRawContent(
		$content,
		'version',
		'auto'
	);

	echo "Field name has an impact of ".$result['name']." %";

?>


.. |buildStatusIcon| image:: https://travis-ci.org/timoschmidt/php-solr-explain.png?branch=master
   :alt: Build Status
   :target: https://travis-ci.org/timoschmidt/php-solr-explain

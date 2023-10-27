# PHP Solr Explain

[![Build Status](https://github.com/TYPO3-Solr/php-solr-explain/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/TYPO3-Solr/php-solr-explain/actions?query=branch%3Amain)
[![Latest Stable Version](https://poser.pugx.org/apache-solr-for-typo3/php-solr-explain/v/stable)](https://packagist.org/packages/apache-solr-for-typo3/php-solr-explain)
[![Latest Unstable Version](https://poser.pugx.org/apache-solr-for-typo3/php-solr-explain/v/unstable)](https://packagist.org/packages/apache-solr-for-typo3/php-solr-explain)
[![License](https://poser.pugx.org/apache-solr-for-typo3/php-solr-explain/license)](https://packagist.org/packages/apache-solr-for-typo3/php-solr-explain)
[![Monthly Downloads](https://poser.pugx.org/apache-solr-for-typo3/php-solr-explain/d/monthly)](https://packagist.org/packages/apache-solr-for-typo3/php-solr-explain)

Apache Solr retrieves a detailed explain output how the score of a result is calculated. This explain-result library
is good for people who know how solr works, but it is also hard to understand.

php-solr-explain is a library that parses this explain output and calculates the impact of each field. Finally this information can be used, e.g.
to display a pie chart that shows the integrator which field had an impact on that match:

![Pie chart with explain information](example.png)

# Thanks

* Thx to Michael Klapper <michael.klapper@aoemedia.de> for helping on the initial version
* Solr.pl, Marek Rogoziński, Rafał Kuć



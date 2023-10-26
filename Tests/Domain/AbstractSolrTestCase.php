<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests;

use PHPUnit\Framework\TestCase;

abstract class AbstractSolrTest extends TestCase
{
    /**
     * Helper method to get the path of a "fixtures" folder that is relative to the current class folder.
     *
     * @return string
     */
    protected function getTestFixturePath()
    {
        $reflector	= new \ReflectionClass(static::class);
        $path 		= dirname($reflector->getFileName()) . '/Fixtures/';
        return $path;
    }

    /**
     * Helper method to get a content of a fixture that is located in the "fixtures" folder beside the
     * current testcase.
     *
     * @param $fixtureFilename
     * @return string
     */
    protected function getFixtureContent($fixtureFilename)
    {
        return file_get_contents($this->getTestFixturePath() . $fixtureFilename);
    }
}

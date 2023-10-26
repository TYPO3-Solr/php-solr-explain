<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

abstract class AbstractSolrTest extends TestCase
{
    /**
     * Helper method to get the path of a "fixtures" folder that is relative to the current class folder.
     */
    protected function getTestFixturePath(): string
    {
        $reflector = new ReflectionClass(static::class);
        return dirname($reflector->getFileName()) . '/Fixtures/';
    }

    /**
     * Helper method to get a content of a fixture that is located in the "fixtures" folder beside the
     * current testcase.
     */
    protected function getFixtureContent(string $fixtureFilename): string
    {
        return file_get_contents($this->getTestFixturePath() . $fixtureFilename);
    }
}

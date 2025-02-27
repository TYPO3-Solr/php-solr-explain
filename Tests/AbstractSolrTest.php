<?php

namespace ApacheSolrForTypo3\SolrExplain\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;

abstract class AbstractSolrTest extends TestCase
{
    /**
     * Helper method to get the path of a "fixtures" folder that is relative to the current class folder.
     */
    protected function getTestFixturePath(): string
    {
        $reflector = new ReflectionClass(static::class);
        if (is_string($reflector->getFileName())) {
            return dirname($reflector->getFileName()) . '/Fixtures/';
        }
        throw new RuntimeException(
            'Failed to get path for fixtures required by: ' . static::class,
            1740699680,
        );
    }

    /**
     * Helper method to get a content of a fixture that is located in the "fixtures" folder beside the
     * current testcase.
     */
    protected function getFixtureContent(string $fixtureFilename): string
    {
        $fileContents = file_get_contents($this->getTestFixturePath() . $fixtureFilename);
        if (is_string($fileContents)) {
            return $fileContents;
        }
        throw new RuntimeException(
            'Failed to read file contents for fixture file: ' . $fixtureFilename,
            1740699685,
        );
    }
}

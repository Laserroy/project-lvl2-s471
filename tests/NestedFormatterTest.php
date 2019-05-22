<?php

namespace DiffGenerator\Tests;

use PHPUnit\Framework\TestCase;
use function DiffGenerator\DifferenceBuilder\buildDiffTree;
use function DiffGenerator\DifferenceFormatter\createNestedDiff;

class NestedFormatterTest extends TestCase
{
    public function testCreateNestedDiff()
    {
        $jsonBeforeData = file_get_contents(__DIR__ . '/fixtures/before.json');
        $jsonAfterData = file_get_contents(__DIR__ . '/fixtures/after.json');
        $jsonResult = file_get_contents(__DIR__ . '/fixtures/nested_result');
        $diffTree = buildDiffTree(json_decode($jsonBeforeData), json_decode($jsonAfterData));
        $this->assertEquals(
            $jsonResult,
            createNestedDiff($diffTree)
        );
    }
}

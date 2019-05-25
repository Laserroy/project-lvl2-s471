<?php
namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\DiffBuilder\buildDiffTree;
use function Differ\DiffFormatter\makeNestedDiff;

class NestedFormatterTest extends TestCase
{
    public function testMakeNestedDiff()
    {
        $jsonBeforeData = file_get_contents(__DIR__ . '/fixtures/before.json');
        $jsonAfterData = file_get_contents(__DIR__ . '/fixtures/after.json');
        $jsonResult = file_get_contents(__DIR__ . '/fixtures/nested_result');
        $diffTree = buildDiffTree(json_decode($jsonBeforeData), json_decode($jsonAfterData));
        $this->assertEquals(
            $jsonResult,
            makeNestedDiff($diffTree)
        );
    }
}

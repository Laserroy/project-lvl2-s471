<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\DiffBuilder\buildDiffTree;
use function Differ\DiffFormatter\makePlainDiff;

class PlainFormatterTest extends TestCase
{
    public function testMakePlainDiff()
    {
        $jsonBeforeData = file_get_contents(__DIR__ . '/fixtures/before.json');
        $jsonAfterData = file_get_contents(__DIR__ . '/fixtures/after.json');
        $jsonResult = file_get_contents(__DIR__ . '/fixtures/plain_result');
        $diffTree = buildDiffTree(json_decode($jsonBeforeData), json_decode($jsonAfterData));
        $this->assertEquals(
            $jsonResult,
            makePlainDiff($diffTree)
        );
    }
}

<?php
namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\getDiff;
use function Funct\Collection\get;

class DifferTest extends TestCase
{
    public function testGetDiff()
    {
        $beforePath = __DIR__ . '/fixtures/before.json';
        $afterPath = __DIR__ . '/fixtures/after.json';
        $nestedResult = file_get_contents(__DIR__ . '/fixtures/nested_result');
        $diff = getDiff($beforePath, $afterPath);
        $this->assertEquals($nestedResult, $diff);

        $plainResult = file_get_contents(__DIR__ . '/fixtures/plain_result');
        $diff2 = getDiff($beforePath, $afterPath, 'plain');
        $this->assertEquals($plainResult, $diff2);

        $beforePath = __DIR__ . '/fixtures/before.yml';
        $afterPath = __DIR__ . '/fixtures/after.yml';
        $diff3 = getDiff($beforePath, $afterPath, 'nested');
        $this->assertEquals($nestedResult, $diff3);

        $diff4 = getDiff($beforePath, $afterPath, 'plain');
        $this->assertEquals($plainResult, $diff4);
    }
}

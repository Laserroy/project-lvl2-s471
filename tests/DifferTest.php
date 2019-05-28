<?php
namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\getDiff;

class DifferTest extends TestCase
{
    public function testGetDiff()
    {
        $jsonBeforePath = __DIR__ . '/fixtures/before.json';
        $jsonAfterPath = __DIR__ . '/fixtures/after.json';
        $nestedResult = file_get_contents(__DIR__ . '/fixtures/nested_result');
        $plainResult = file_get_contents(__DIR__ . '/fixtures/plain_result');
        
        $diff = getDiff($jsonBeforePath, $jsonAfterPath);
        $this->assertEquals($nestedResult, $diff);

        $diff2 = getDiff($jsonBeforePath, $jsonAfterPath, 'plain');
        $this->assertEquals($plainResult, $diff2);

        $yamlBeforePath = __DIR__ . '/fixtures/before.yml';
        $YamlAfterPath = __DIR__ . '/fixtures/after.yml';
        
        $diff3 = getDiff($yamlBeforePath, $YamlAfterPath);
        $this->assertEquals($nestedResult, $diff3);

        $diff4 = getDiff($yamlBeforePath, $YamlAfterPath, 'plain');
        $this->assertEquals($plainResult, $diff4);
    }
}

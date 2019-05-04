<?php

namespace DiffGenerator\Tests;

use PHPUnit\Framework\TestCase;
use function DiffGenerator\FilesComparator\filesParser;

class FilesComparatorTest extends TestCase
{
    public function testFilesParser()
    {
        $before = [
            "host" => "hexlet.io",
            "timeout" => 50,
            "proxy" => "123.234.53.22"
        ];
        $after = [
            "timeout" => 20,
            "verbose" => true,
             "host" => "hexlet.io"
        ];
        $diff = [
            "  host" => "hexlet.io",
            "+ timeout" => 20,
            "- timeout" => 50,
            "- proxy" => "123.234.53.22",
            "+ verbose" => true
        ];
        $result = filesParser($before, $after);
        $this->assertEquals($diff, $result);
    }
}

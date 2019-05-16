<?php

namespace DiffGenerator\Tests;

use PHPUnit\Framework\TestCase;
use function DiffGenerator\DifferenceBuilder\buildDiffTree;
use function DiffGenerator\DifferenceFormatter\renderDiff;
use function DiffGenerator\DifferenceFormatter\renderPlainDiff;

class FilesComparatorTest extends TestCase
{
    

    public function testBuildDiffTree()
    {
        $a = '{
        "common": {
          "setting1": "Value 1",
          "setting2": "200",
          "setting3": true,
          "setting6": {
            "key": "value"
          }
        },
        "group1": {
          "baz": "bas",
          "foo": "bar"
        },
        "group2": {
          "abc": "12345"
        }
      }';
    
        $b = '{
        "common": {
          "setting1": "Value 1",
          "setting3": true,
          "setting4": "blah blah",
          "setting5": {
            "key5": "value5"
          }
        },
      
        "group1": {
          "foo": "bar",
          "baz": "bars"
        },
      
        "group3": {
          "fee": "100500"
        }
      }';
        $c =
        "{
    common: {
        setting1: Value 1
      - setting2: 200
        setting3: true
      - setting6: {
            key: value
        }
      + setting4: blah blah
      + setting5: {
            key5: value5
        }
    }
    group1: {
      + baz: bars
      - baz: bas
        foo: bar
    }
  - group2: {
        abc: 12345
    }
  + group3: {
        fee: 100500
    }
}\n";
        $adata = json_decode($a, true);
        $bdata = json_decode($b, true);
        
      
          $result = buildDiffTree($adata, $bdata);
          $resultString = renderDiff($result);
          $this->assertEquals($c, $resultString);
    }
}

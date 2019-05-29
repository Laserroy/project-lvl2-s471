<?php
namespace Differ\DiffFormatter;

function boolToString($value)
{
    if ($value === true) {
        return "true";
    }
    if ($value === false) {
        return "false";
    }
    return $value;
}

function getOffset($depth)
{
    return str_repeat(" ", $depth * 4);
}

function getValueStringRepresent($value, $depth):string
{
    if (is_object($value)) {
        $content = get_object_vars($value);
        $keys = array_keys($content);
        $dataOffset = getOffset($depth + 2);
        $bracketOffset = getOffset($depth + 1);
        $converted = array_map(function ($key) use ($dataOffset, $content) {
            $value = boolToString($content[$key]);
            return "{$dataOffset}{$key}: {$value}";
        }, $keys);
        $represent = implode("\n", $converted);
        return "{\n{$represent}\n{$bracketOffset}}\n";
    }
    $converted = boolToString($value);
    return "{$converted}\n";
}

function makeNestedDiff($diffTree, $depth = 0)
{
    $result = array_reduce(
        $diffTree,
        function ($acc, $node) use ($depth) {
            ["type" => $type,
             "name" => $name,
             "oldValue" => $oldValue,
             "newValue" => $newValue,
             "children" => $children] = $node;
             $offset = getOffset($depth);
            switch ($type) {
                case "added":
                    $resultString = getValueStringRepresent($newValue, $depth);
                    $acc[] = "{$offset}  + {$name}: {$resultString}";
                    return $acc;
                case "removed":
                    $resultString = getValueStringRepresent($oldValue, $depth);
                    $acc[] = "{$offset}  - {$name}: {$resultString}";
                    return $acc;
                case "changed":
                    $oldValueString = getValueStringRepresent($oldValue, $depth);
                    $newValueString = getValueStringRepresent($newValue, $depth);
                    $acc[] = "{$offset}  + {$name}: {$newValueString}";
                    $acc[] = "{$offset}  - {$name}: {$oldValueString}";
                    return $acc;
                case "unchanged":
                    $resultString = getValueStringRepresent($oldValue, $depth);
                    $acc[] = "{$offset}    {$name}: {$resultString}";
                    return $acc;
                case "nested":
                    $newDepth = $depth + 1;
                    $newOffset = getOffset($newDepth);
                    $resultString = makeNestedDiff($children, $newDepth);
                    $acc[] = "{$newOffset}{$name}: {$resultString}";
                    return $acc;
            }
        },
        []
    );
    $offset = getOffset($depth);
    $resultString = implode("", $result);
    return "{\n{$resultString}{$offset}}\n";
}

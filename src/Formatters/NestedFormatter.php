<?php
namespace Differ\DiffFormatter;

function makeNestedDiff($diffTree, $depth = 0)
{
    $diff = array_reduce(
        $diffTree,
        function ($acc, $node) use ($depth) {
            ['type' => $type,
             'name' => $name,
             'oldValue' => $oldValue,
             'newValue' => $newValue,
             'children' => $children] = $node;
             
            $offset = getOffset($depth);
            $oldValueString = getDiffRepresent($oldValue, $depth);
            $newValueString = getDiffRepresent($newValue, $depth);
            switch ($type) {
                case 'added':
                    $acc[] = "{$offset}  + {$name}: {$newValueString}";
                    return $acc;
                case 'removed':
                    $acc[] = "{$offset}  - {$name}: {$oldValueString}";
                    return $acc;
                case 'changed':
                    $acc[] = "{$offset}  + {$name}: {$newValueString}";
                    $acc[] = "{$offset}  - {$name}: {$oldValueString}";
                    return $acc;
                case 'unchanged':
                    $acc[] = "{$offset}    {$name}: {$oldValueString}";
                    return $acc;
                case 'nested':
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
    $nestedDiff = implode("", $diff);
    return "{\n{$nestedDiff}{$offset}}\n";
}

function getDiffRepresent($value, $depth):string
{
    if (!is_object($value)) {
        $convertedValue = boolToString($value);
        return "{$convertedValue}\n";
    }
    $content = get_object_vars($value);
    $keys = array_keys($content);
    $dataOffset = getOffset($depth + 2);
    $bracketOffset = getOffset($depth + 1);
    $convertedValue = array_map(function ($key) use ($dataOffset, $content) {
        $value = boolToString($content[$key]);
        return "{$dataOffset}{$key}: {$value}";
    }, $keys);
    $arrayRepresent = implode("\n", $convertedValue);
    return "{\n{$arrayRepresent}\n{$bracketOffset}}\n";
}

function boolToString($value)
{
    if ($value === true) {
        return 'true';
    }
    if ($value === false) {
        return 'false';
    }
    return $value;
}

function getOffset($depth)
{
    return str_repeat(" ", $depth * 4);
}

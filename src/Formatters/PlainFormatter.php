<?php
namespace Differ\DiffFormatter;

function makePlainDiff($diffTree, $prevNames = [])
{
    $diffLines = array_reduce($diffTree, function ($acc, $node) use ($prevNames) {
        ['type' => $type,
         'name' => $name,
         'oldValue' => $oldValue,
         'newValue' => $newValue,
         'children' => $children] = $node;
        
        $prevNames[] = $name;
        $propertyName = implode(".", $prevNames);
        $valueBefore = is_object($oldValue) ? 'complex value' : boolToString($oldValue);
        $valueAfter = is_object($newValue) ? 'complex value' : boolToString($newValue);
        switch ($type) {
            case 'changed':
                $acc[] = "Property '{$propertyName}' was changed. From '{$valueBefore}' to '{$valueAfter}'";
                return $acc;
            case 'added':
                $acc[] = "Property '{$propertyName}' was added with value: '{$valueAfter}'";
                return $acc;
            case 'removed':
                $acc[] = "Property '{$propertyName}' was removed";
                return $acc;
            case 'nested':
                $acc[] = makePlainDiff($children, $prevNames);
                return $acc;
            case 'unchanged':
                return $acc;
        }
    }, []);
    $diffLines = implode("\n", $diffLines);
    return $diffLines;
}

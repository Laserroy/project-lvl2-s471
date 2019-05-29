<?php
namespace Differ\DiffFormatter;

function buildDiffLine($type, $name, $oldValue, $newValue, $prevNames = [])
{
    $prevNames[] = $name;
    $propertyName = implode(".", $prevNames);
    if ($type === "changed") {
        $valueBefore = is_object($oldValue) ? "complex value" : boolToString($oldValue);
        $valueAfter = is_object($newValue) ? "complex value" : boolToString($newValue);
        $diffLine = "Property '$propertyName' was changed. From '{$valueBefore}' to '{$valueAfter}'\n";
        return $diffLine;
    }
    if ($type === "added") {
        $valueAfter = is_object($newValue) ? "complex value" : boolToString($newValue);
        $diffLine = "Property '{$propertyName}' was added with value: '{$valueAfter}'\n";
        return $diffLine;
    }
    if ($type === "removed") {
        $diffLine = "Property '{$propertyName}' was removed\n";
        return $diffLine;
    }
}

function makePlainDiff($diffTree, $prevNames = []):string
{
    $plainDiff = array_reduce($diffTree, function ($acc, $node) use ($prevNames) {
        ["type" => $type,
         "name" => $name,
         "oldValue" => $oldValue,
         "newValue" => $newValue,
         "children" => $children] = $node;
        if ($type === "nested") {
            $prevNames[] = $name;
            $acc[] = makePlainDiff($children, $prevNames);
            return $acc;
        } else {
            $acc[] = buildDiffLine($type, $name, $oldValue, $newValue, $prevNames);
            return $acc;
        }
    }, []);
    return implode("", $plainDiff);
}

<?php
namespace Differ\ASTbuilder;

use function Funct\Collection\union;

function createNode($type, $name, $oldValue, $newValue, $children)
{
    $node = [
        "type" => $type,
        "name" => $name,
        "oldValue" => $oldValue,
        "newValue" => $newValue,
        "children" => $children
    ];
    return $node;
}

function buildAST($beforeData, $afterData):array
{
    $beforeArray = get_object_vars($beforeData);
    $afterArray = get_object_vars($afterData);
    $commonKeys = union(array_keys($beforeArray), array_keys($afterArray));
    $diffTree = array_reduce(
        $commonKeys,
        function ($acc, $key) use ($beforeArray, $afterArray) {
            if (array_key_exists($key, $beforeArray) && !array_key_exists($key, $afterArray)) {
                $acc[] = createNode("removed", $key, $beforeArray[$key], null, null);
                return $acc;
            } elseif (!array_key_exists($key, $beforeArray) && array_key_exists($key, $afterArray)) {
                $acc[] = createNode("added", $key, null, $afterArray[$key], null);
                return $acc;
            } else {
                if ($beforeArray[$key] === $afterArray[$key]) {
                    $acc[] = createNode("unchanged", $key, $beforeArray[$key], null, null);
                    return $acc;
                }
                if (is_object($afterArray[$key]) && is_object($beforeArray[$key])) {
                    $children = buildAST($beforeArray[$key], $afterArray[$key]);
                    $acc[] = createNode("nested", $key, null, null, $children);
                    return $acc;
                } else {
                    $oldValue = $beforeArray[$key];
                    $newValue = $afterArray[$key];
                    $acc[] = createNode("changed", $key, $oldValue, $newValue, null);
                    return $acc;
                }
            }
        },
        []
    );
    return $diffTree;
}

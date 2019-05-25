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

function makeNestedDiff($diffTree, $offset = "")
{
    $result = array_reduce(
        $diffTree,
        function ($acc, $current) use ($offset) {
            if ($current["children"] === "nested") {
                $newOffset = $offset . "    ";
                $resultString = makeNestedDiff($current["value"], $newOffset);
                $acc[]= "{$offset}  {$current["status"]} {$current["name"]}: {$resultString}";
                return $acc;
            }
            if (is_object($current["value"])) {
                $currentArray = get_object_vars($current["value"]);
                $newOffset = empty($offset) ? "        " : $offset . "    ";
                $result = [];
                foreach ($currentArray as $k => $v) {
                    $value = boolToString($v);
                    $result[] = "{$newOffset}{$offset}{$k}: {$value}\n";
                }
                $resultString = implode("", $result);
                $acc[] = "{$offset}  {$current["status"]} {$current["name"]}: {\n{$resultString}{$offset}    }\n";
                return $acc;
            } else {
                $currentValue = boolToString($current["value"]);
                $acc[] = "{$offset}  {$current["status"]} {$current["name"]}: {$currentValue}\n";
                return $acc;
            }
        },
        []
    );
    $resultString = implode("", $result);
    return "{\n{$resultString}{$offset}}\n";
}

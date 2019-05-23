<?php
namespace DiffGenerator\DifferenceFormatter;

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

function createNestedDiff($diffTree, $offset = "")
{
    $result = array_reduce(
        $diffTree,
        function ($acc, $current) use ($offset) {
            if ($current["children"] === "nested") {
                $newOffset = $offset . "    ";
                $resultString = createNestedDiff($current["value"], $newOffset);
                $acc .= $offset . "  " . $current["status"] . " " . $current["name"] . ": " . $resultString;
                return $acc;
            }
            if (is_object($current["value"])) {
                $current["value"] = get_object_vars($current["value"]);
                $newOffset = empty($offset) ? "        " : $offset . "    ";
                $resultString = '';
                foreach ($current["value"] as $k => $v) {
                    $resultString .= $newOffset . $offset . $k . ": " . boolToString($v) . "\n";
                }
                $acc .= "{$offset}  {$current["status"]} {$current["name"]}: {\n" . $resultString . $offset . "    }\n";
                return $acc;
            } else {
                $acc .= "{$offset}  {$current["status"]} {$current["name"]}: " . boolToString($current["value"]) . "\n";
                return $acc;
            }
        },
        ""
    );
    return "{\n" . $result . $offset . "}\n";
}

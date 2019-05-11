<?php

namespace DiffGenerator\FilesComparator;

use function Funct\Collection\union;

function isAssocArray($array)
{
    if (!is_array($array)) {
        return false;
    }
    foreach (array_keys($array) as $key) {
        if (!is_int($key)) {
            return true;
        }
        return false;
    }
}

function buildDiffTree($beforeData, $afterData)
{
    $aKeys = array_keys($beforeData);
    $bKeys = array_keys($afterData);
    $commonKeys = union($aKeys, $bKeys);
    $diffTree = array_reduce(
        $commonKeys,
        function ($acc, $key) use ($beforeData, $afterData) {
            if (array_key_exists($key, $beforeData) && !array_key_exists($key, $afterData)) {
                   $acc[] = ["name" => "- $key",
                    "value" => $beforeData[$key],
                    "children" => null
                   ];
                   return $acc;
            } elseif (!array_key_exists($key, $beforeData) && array_key_exists($key, $afterData)) {
                $acc[] = ["name" => "+ $key",
                "value" => $afterData[$key],
                "children" => null
                ];
                return $acc;
            } else {
                if ($beforeData[$key] === $afterData[$key]) {
                    $acc[] = ["name" => "  $key",
                    "value" => $afterData[$key],
                    "children" => null
                    ];
                    return $acc;
                }
                if (isAssocArray($afterData[$key]) && isAssocArray($beforeData[$key])) {
                    $acc[] = ["name" => "  $key",
                    "value" => buildDiffTree($beforeData[$key], $afterData[$key]),
                    "children" => "nested"
                    ];
                    return $acc;
                } else {
                    $acc[] = ["name" => "+ $key",
                    "value" => $afterData[$key],
                    "children" => null
                    ];
                    $acc[] = ["name" => "- $key",
                    "value" => $beforeData[$key],
                    "children" => null
                    ];
                    return $acc;
                }
            }
        },
        []
    );
    return $diffTree;
}
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

function renderDiff($diffTree, $offset = "")
{
    $result = array_reduce(
        $diffTree,
        function ($acc, $current) use ($offset) {
            if ($current["children"] === "nested") {
                $newOffset = $offset . "    ";
                $resultString = renderDiff($current["value"], $newOffset);
                $acc .= $offset . "  " . $current["name"] . ": " . $resultString;
                return $acc;
            }
            if (is_array($current["value"])) {
                $newOffset = empty($offset) ? "        " : $offset . "    ";
                $resultString = '';
                foreach ($current["value"] as $k => $v) {
                    $resultString .= $newOffset . $offset . $k . ": " . boolToString($v) . "\n";
                }
                $acc .= $offset . "  " . $current["name"] . ": {\n" . $resultString . $offset . "    }\n";
                return $acc;
            } else {
                $acc .= $offset . "  " . $current["name"] . ": " . boolToString($current["value"]) . "\n";
                return $acc;
            }
        },
        ""
    );
    $result2 = "{\n" . $result . $offset . "}\n";
    return $result2;
}

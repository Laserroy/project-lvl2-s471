<?php
namespace Differ\DiffBuilder;

use function Funct\Collection\union;

function buildDiffTree($beforeData, $afterData)
{
    $beforeArray = get_object_vars($beforeData);
    $afterArray = get_object_vars($afterData);
    $commonKeys = union(array_keys($beforeArray), array_keys($afterArray));
    $diffTree = array_reduce(
        $commonKeys,
        function ($acc, $key) use ($beforeArray, $afterArray) {
            if (array_key_exists($key, $beforeArray) && !array_key_exists($key, $afterArray)) {
                $acc[] = [
                "status" => "-",
                "name" => $key,
                "children" => null,
                "value" => $beforeArray[$key]
                ];
                return $acc;
            } elseif (!array_key_exists($key, $beforeArray) && array_key_exists($key, $afterArray)) {
                $acc[] = [
                "status" => "+",
                "name" => $key,
                "children" => null,
                "value" => $afterArray[$key]
                ];
                return $acc;
            } else {
                if ($beforeArray[$key] === $afterArray[$key]) {
                    $acc[] = [
                    "status" => " ",
                    "name" => $key,
                    "children" => null,
                    "value" => $afterArray[$key]
                    ];
                    return $acc;
                }
                if (is_object($afterArray[$key]) && is_object($beforeArray[$key])) {
                    $acc[] = [
                    "status" => " ",
                    "name" => $key,
                    "children" => "nested",
                    "value" => buildDiffTree($beforeArray[$key], $afterArray[$key])
                    ];
                    return $acc;
                } else {
                    $acc[] = [
                    "status" => "+",
                    "name" => $key,
                    "children" => null,
                    "value" => $afterArray[$key]
                    ];
                    $acc[] = [
                    "status" => "-",
                    "name" => $key,
                    "children" => null,
                    "value" => $beforeArray[$key]
                    ];
                    return $acc;
                }
            }
        },
        []
    );
    return $diffTree;
}

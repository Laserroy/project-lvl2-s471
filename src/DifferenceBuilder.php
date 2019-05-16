<?php

namespace DiffGenerator\DifferenceBuilder;

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
    $beforeKeys = array_keys($beforeData);
    $afterKeys = array_keys($afterData);
    $commonKeys = union($beforeKeys, $afterKeys);
    $diffTree = array_reduce(
        $commonKeys,
        function ($acc, $key) use ($beforeData, $afterData) {
            if (array_key_exists($key, $beforeData) && !array_key_exists($key, $afterData)) {
                   $acc[] = [
                    "status" => "-",
                    "name" => $key,
                    "children" => null,
                    "value" => $beforeData[$key]
                   ];
                   return $acc;
            } elseif (!array_key_exists($key, $beforeData) && array_key_exists($key, $afterData)) {
                $acc[] = [
                "status" => "+",
                "name" => $key,
                "children" => null,
                "value" => $afterData[$key]
                ];
                return $acc;
            } else {
                if ($beforeData[$key] === $afterData[$key]) {
                    $acc[] = [
                    "status" => " ",
                    "name" => $key,
                    "children" => null,
                    "value" => $afterData[$key]
                    ];
                    return $acc;
                }
                if (isAssocArray($afterData[$key]) && isAssocArray($beforeData[$key])) {
                    $acc[] = [
                    "status" => " ",
                    "name" => $key,
                    "children" => "nested",
                    "value" => buildDiffTree($beforeData[$key], $afterData[$key])
                    ];
                    return $acc;
                } else {
                    $acc[] = [
                    "status" => "+",
                    "name" => $key,
                    "children" => null,
                    "value" => $afterData[$key]
                    ];
                    $acc[] = [
                    "status" => "-",
                    "name" => $key,
                    "children" => null,
                    "value" => $beforeData[$key]
                    ];
                    return $acc;
                }
            }
        },
        []
    );
    return $diffTree;
}

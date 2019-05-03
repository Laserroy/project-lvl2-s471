<?php

namespace DiffGenerator\FilesComparator;

use function Funct\Collection\union;

function filesParser($data1, $data2)
{
    $unitedArray = union($data1, $data2);
    $result = [];
    foreach ($unitedArray as $k => $v) {
        if (!array_key_exists($k, $data2)) {
            $result["- " . $k] = $v;
        } elseif (!array_key_exists($k, $data1)) {
            $result["+ " . $k] = $v;
        } elseif ($data1[$k] === $data2[$k]) {
            $result["  " . $k] = $v;
        } else {
            $result["+ " . $k] = $v;
            $result["- " . $k] = $data1[$k];
        }
    }
    return $result;
}

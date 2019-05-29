<?php
namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse($data, $extension)
{
    $mappingForParsing = [
        "json" => function ($data) {
            return json_decode($data);
        },
        "yml" => function ($data) {
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        },
        "ini" => function ($data) {
            $parsedArray = parse_ini_string($data, false, INI_SCANNER_RAW);
            return (object) $parsedArray;
        }
    ];
    return $mappingForParsing[$extension]($data);
}

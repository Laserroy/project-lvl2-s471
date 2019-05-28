<?php
namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function getFileExtension($path)
{
    $pathInfo = pathinfo($path);
    $result = $pathInfo['extension'] ?? null;
    return $result;
}

function parseFile($path)
{
    $mappingForParsing = [
        "json" => function ($filePath) {
            return json_decode(file_get_contents($filePath));
        },
        "yml" => function ($filePath) {
            return Yaml::parseFile($filePath, Yaml::PARSE_OBJECT_FOR_MAP);
        },
        "ini" => function ($filePath) {
            return parse_ini_file($filePath, false, INI_SCANNER_RAW);
        }
    ];
    $fileExtension = getFileExtension($path);
    return $mappingForParsing[$fileExtension]($path);
}

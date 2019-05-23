<?php
namespace DiffGenerator;

use Symfony\Component\Yaml\Yaml;
use function DiffGenerator\DifferenceBuilder\buildDiffTree;
use function DiffGenerator\DifferenceFormatter\createNestedDiff;
use function DiffGenerator\DifferenceFormatter\createPlainDiff;


function getFileExtension($path)
{
    $pathInfo = pathinfo($path);
    return $pathInfo['extension'];
}

function getFilesDiff($pathToFile1, $pathToFile2, $requestedFormat = 'nested')
{
    if (getFileExtension($pathToFile1) === getFileExtension($pathToFile2)) {
        $extension = getFileExtension($pathToFile1);
        switch ($extension) {
            case 'json':
                $file1 = file_get_contents($pathToFile1);
                $file2 = file_get_contents($pathToFile2);
                $data1 = json_decode($file1);
                $data2 = json_decode($file2);
                $diffAST = buildDiffTree($data1, $data2);
                break;
            case 'yml':
                $data1 = Yaml::parseFile($pathToFile1, Yaml::PARSE_OBJECT_FOR_MAP);
                $data2 = Yaml::parseFile($pathToFile2, Yaml::PARSE_OBJECT_FOR_MAP);
                $diffAST = buildDiffTree($data1, $data2);
                break;
            case 'ini':
                $data1 = parse_ini_file($pathToFile1, false, INI_SCANNER_RAW);
                $data2 = parse_ini_file($pathToFile2, false, INI_SCANNER_RAW);
                $diffAST = buildDiffTree($data1, $data2);
                break;
        }
        switch ($requestedFormat) {
            case 'nested':
                return createNestedDiff($diffAST);
            case 'plain':
                return createPlainDiff($diffAST);
            case 'json':
                return json_encode($diffAST);
        }
    }
}

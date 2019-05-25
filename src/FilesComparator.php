<?php
namespace Differ;

use Symfony\Component\Yaml\Yaml;
use function Differ\DiffBuilder\buildDiffTree;
use function Differ\DiffFormatter\makeNestedDiff;
use function Differ\DiffFormatter\makePlainDiff;


function getFileExtension($path)
{
    $pathInfo = pathinfo($path);
    return $pathInfo['extension'];
}

function getDiff($pathToFile1, $pathToFile2, $requestedFormat = 'nested')
{
    if (getFileExtension($pathToFile1) === getFileExtension($pathToFile2)) {
        $extension = getFileExtension($pathToFile1);
        switch ($extension) {
            case 'json':
                $beforeData = json_decode(file_get_contents($pathToFile1));
                $afterData = json_decode(file_get_contents($pathToFile2));
                $diffAST = buildDiffTree($beforeData, $afterData);
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
                return makeNestedDiff($diffAST);
            case 'plain':
                return makePlainDiff($diffAST);
            case 'json':
                return json_encode($diffAST);
        }
    }
}

<?php
namespace DiffGenerator;

use Symfony\Component\Yaml\Yaml;
use function DiffGenerator\DifferenceBuilder\buildDiffTree;


function getFileExtension($path)
{
    $pathInfo = pathinfo($path);
    return $pathInfo['extension'];
}

function makeFilesCompare($pathToFile1, $pathToFile2)
{
    if (getFileExtension($pathToFile1) === getFileExtension($pathToFile2)) {
        switch (getFileExtension($pathToFile1)) {
            case 'json':
                $file1 = file_get_contents($pathToFile1);
                $file2 = file_get_contents($pathToFile2);
                $data1 = json_decode($file1);
                $data2 = json_decode($file2);
                return buildDiffTree($data1, $data2);
            case 'yml':
                $data1 = Yaml::parseFile($pathToFile1, Yaml::PARSE_OBJECT_FOR_MAP);
                $data2 = Yaml::parseFile($pathToFile2, Yaml::PARSE_OBJECT_FOR_MAP);
                return buildDiffTree($data1, $data2);
            case 'ini':
                $data1 = parse_ini_file($pathToFile1, false, INI_SCANNER_RAW);
                $data2 = parse_ini_file($pathToFile2, false, INI_SCANNER_RAW);
                return buildDiffTree($data1, $data2);
        }
    }
}

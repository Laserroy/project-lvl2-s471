<?php
namespace DiffGenerator;

use Docopt;
use Symfony\Component\Yaml\Yaml;
use function DiffGenerator\DifferenceBuilder\buildDiffTree;
use function DiffGenerator\DifferenceFormatter\renderDiff;
use function DiffGenerator\DifferenceFormatter\renderPlainDiff;


function getFileExtension($path)
{
    $pathInfo = pathinfo($path);
    return $pathInfo['extension'];
}

function run($docDescription, $params)
{
    $args = Docopt::handle($docDescription, $params);
    $transformedArgs = json_decode(json_encode($args), true);
    $givenArguments = $transformedArgs['args'];
    $pathToFile1 = $givenArguments['PATH1'] ?? null;
    $pathToFile2 = $givenArguments['PATH2'] ?? null;
    
    if (getFileExtension($pathToFile1) === getFileExtension($pathToFile2)) {
        switch (getFileExtension($pathToFile1)) {
            case 'json':
                $file1 = file_get_contents($pathToFile1);
                $file2 = file_get_contents($pathToFile2);
                $data1 = json_decode($file1, true);
                $data2 = json_decode($file2, true);
                $difference = buildDiffTree($data1, $data2);
                break;
            case 'yml':
                $data1 = Yaml::parseFile($pathToFile1);
                $data2 = Yaml::parseFile($pathToFile2);
                $difference = buildDiffTree($data1, $data2);
                break;
            case 'ini':
                $data1 = parse_ini_file($pathToFile1, false, INI_SCANNER_RAW);
                $data2 = parse_ini_file($pathToFile2, false, INI_SCANNER_RAW);
                $difference = buildDiffTree($data1, $data2);
                break;
        }
        switch ($givenArguments["--format"]) {
            case null:
                $result = renderDiff($difference);
                echo $result;
                break;
            case 'plain':
                $result = "\n" . renderPlainDiff($difference) . "\n";
                echo $result;
                break;
            case 'json':
                $result = json_encode($difference);
                echo "\n", $result, "\n";
                return $result;
                break;
        }
    }
}

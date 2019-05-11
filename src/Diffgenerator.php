<?php
namespace DiffGenerator;
use Docopt;

use Symfony\Component\Yaml\Yaml;
use function DiffGenerator\FilesComparator\buildDiffTree;
use function DiffGenerator\FilesComparator\renderDiff;

function run($docDescription, $params)
{
    $args = Docopt::handle($docDescription, $params);
    $transformedArgs = json_decode(json_encode($args), true);
    $givenArguments = $transformedArgs['args'];
    $pathToFile1 = $givenArguments['PATH1'] ?? null;
    $pathToFile2 = $givenArguments['PATH2'] ?? null;
    
    function getFileExtension($path)
    {
        $pathInfo = pathinfo($path);
        return $pathInfo['extension'];
    }
    
    if (getFileExtension($pathToFile1) === getFileExtension($pathToFile2)) {
        switch (getFileExtension($pathToFile1)) {
            case 'json':
                $file1 = file_get_contents($pathToFile1);
                $file2 = file_get_contents($pathToFile2);
                $data1 = json_decode($file1, true);
                $data2 = json_decode($file2, true);
                $result = buildDiffTree($data1, $data2);
                print_r(renderDiff($result));
                break;
            case 'yml':
                $data1 = Yaml::parseFile($pathToFile1);
                $data2 = Yaml::parseFile($pathToFile2);
                $result = buildDiffTree($data1, $data2);
                print_r(renderDiff($result));
                break;
        }
    }
}

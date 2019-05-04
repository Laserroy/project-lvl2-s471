<?php
namespace DiffGenerator;
use Docopt;
use function DiffGenerator\FilesComparator\filesParser;
use Symfony\Component\Yaml\Yaml;
use function DiffGenerator\FilesComparator\renderDifference;

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
                return json_encode(filesParser($data1, $data2));
                break;
            case 'yml':
                $data1 = Yaml::parseFile($pathToFile1);
                $data2 = Yaml::parseFile($pathToFile2);
                $result = filesParser($data1, $data2);
                renderDifference($result);
                break;
        }
    }
}

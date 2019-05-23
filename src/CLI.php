<?php
namespace DiffGenerator\Cli;

use Docopt;
use function DiffGenerator\getFilesDiff;

function runCommandLineActions($docDescription, $params)
{
    $args = Docopt::handle($docDescription, $params);
    $transformedArgs = json_decode(json_encode($args), true);
    $givenArguments = $transformedArgs['args'];
    $pathToFile1 = $givenArguments['PATH1'] ?? null;
    $pathToFile2 = $givenArguments['PATH2'] ?? null;
    $requestedFormat = $givenArguments['--format'] ?? 'nested';
    $difference = getFilesDiff($pathToFile1, $pathToFile2, $requestedFormat);
    echo $difference, "\n";
}

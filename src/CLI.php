<?php
namespace DiffGenerator\Cli;

use Docopt;
use function DiffGenerator\makeFilesCompare;
use function DiffGenerator\DifferenceFormatter\createNestedDiff;
use function DiffGenerator\DifferenceFormatter\createPlainDiff;

function runCommandLineActions($docDescription, $params)
{
    $args = Docopt::handle($docDescription, $params);
    $transformedArgs = json_decode(json_encode($args), true);
    $givenArguments = $transformedArgs['args'];
    $pathToFile1 = $givenArguments['PATH1'] ?? null;
    $pathToFile2 = $givenArguments['PATH2'] ?? null;
    $difference = makeFilesCompare($pathToFile1, $pathToFile2);
    switch ($givenArguments["--format"]) {
        case null:
            echo createNestedDiff($difference), "\n";
            break;
        case 'plain':
            echo createPlainDiff($difference), "\n";
            break;
        case 'json':
            return json_encode($difference);
    }
}

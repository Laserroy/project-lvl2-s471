<?php
namespace Differ\Cli;

use Docopt;
use function Differ\getDiff;

function run($docDescription, $params)
{
    $args = Docopt::handle($docDescription, $params);
    $transformedArgs = json_decode(json_encode($args), true);
    $givenArguments = $transformedArgs['args'];
    $pathToFile1 = $givenArguments['PATH1'] ?? null;
    $pathToFile2 = $givenArguments['PATH2'] ?? null;
    $requestedFormat = $givenArguments['--format'] ?? 'nested';
    $difference = getDiff($pathToFile1, $pathToFile2, $requestedFormat);
    print_r($difference);
    print_r("\n");
}

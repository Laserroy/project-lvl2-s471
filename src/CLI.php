<?php
namespace Differ\Cli;

use Docopt;
use function Differ\getDiff;

function run($docDescription, $params)
{
    $givenArguments = Docopt::handle($docDescription, $params);
    $pathToFile1 = $givenArguments->args['PATH1'];
    $pathToFile2 = $givenArguments->args['PATH2'];
    $requestedFormat = $givenArguments->args['--format'];
    $difference = getDiff($pathToFile1, $pathToFile2, $requestedFormat);
    echo $difference, "\n";
}

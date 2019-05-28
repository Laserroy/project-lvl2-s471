<?php
namespace Differ;

use function Differ\ASTBuilder\buildAST;
use function Differ\DiffFormatter\makeNestedDiff;
use function Differ\DiffFormatter\makePlainDiff;
use function Differ\Parser\parseFile;

function getDiff($pathToFile1, $pathToFile2, $requestedFormat = 'nested'):string
{
    $formatMapping = [
        "nested" => function ($diffAST) {
            return makeNestedDiff($diffAST);
        },
        "plain" => function ($diffAST) {
            return makePlainDiff($diffAST);
        },
        "json" => function ($diffAST) {
            return json_encode($diffAST);
        }
    ];
    
    $beforeData = parseFile($pathToFile1);
    $afterData = parseFile($pathToFile2);
    $diffAST = buildAST($beforeData, $afterData);
    return $formatMapping[$requestedFormat]($diffAST);
}

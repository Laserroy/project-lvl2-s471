<?php
namespace Differ;

use function Differ\ASTBuilder\buildAST;
use function Differ\DiffFormatter\makeNestedDiff;
use function Differ\DiffFormatter\makePlainDiff;
use function Differ\Parser\parse;

function getDiff($firstFilePath, $secondFilePath, $requestedFormat = 'nested'):string
{
    $formatMapping = [
        'nested' => function ($diffAST) {
            return makeNestedDiff($diffAST);
        },
        'plain' => function ($diffAST) {
            return makePlainDiff($diffAST);
        },
        'json' => function ($diffAST) {
            return json_encode($diffAST);
        }
    ];
    $firstDataType = pathinfo($firstFilePath)['extension'];
    $secondDataType = pathinfo($secondFilePath)['extension'];
    $firstFileContent = file_get_contents($firstFilePath);
    $secondFileContent = file_get_contents($secondFilePath);
    $beforeData = parse($firstFileContent, $firstDataType);
    $afterData = parse($secondFileContent, $secondDataType);
    $diffAST = buildAST($beforeData, $afterData);
    return $formatMapping[$requestedFormat]($diffAST);
}

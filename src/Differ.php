<?php
namespace Differ;

use function Differ\ASTBuilder\buildAST;
use function Differ\DiffFormatter\makeNestedDiff;
use function Differ\DiffFormatter\makePlainDiff;
use function Differ\Parser\parse;

function getDiff($firstFilePath, $secondFilePath, $requestedFormat = 'nested'):string
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
    $extension = getCommonExtension($firstFilePath, $secondFilePath);
    $firstFileContent = file_get_contents($firstFilePath);
    $secondFileContent = file_get_contents($secondFilePath);
    $beforeData = parse($firstFileContent, $extension);
    $afterData = parse($secondFileContent, $extension);
    $diffAST = buildAST($beforeData, $afterData);
    return $formatMapping[$requestedFormat]($diffAST);
}

function getCommonExtension($filePath1, $filePath2)
{
    $firstExtension = pathinfo($filePath1)['extension'];
    $secondExtension = pathinfo($filePath2)['extension'];
    if ($firstExtension === $secondExtension) {
        return $firstExtension;
    }
}

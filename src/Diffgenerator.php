<?php
namespace DiffGenerator;
use Docopt;
use function DiffGenerator\FilesComparator\filesParser;

function run()
{
    $doc = <<<'DOCOPT'
        Example of program which uses [options] shortcut in pattern.
        Usage:
            gendiff (-h | --help)
            gendiff --version
            gendiff (PATH1) (PATH2)

 
        Options:
            -h --help                Show this help message and exit
            --version                Show version.
DOCOPT;
    $params = array(
        'help' => true,
        'version' => 'v0.0.1',
    );
    $args = Docopt::handle($doc, $params);
    $array = json_decode(json_encode($args), true);

    if (isset($array['args']['PATH1']) && isset($array['args']['PATH2'])) {
        $file1 = file_get_contents($array['args']['PATH1']);
        $file2 = file_get_contents($array['args']['PATH2']);
        filesParser($file1, $file2);
    }
}

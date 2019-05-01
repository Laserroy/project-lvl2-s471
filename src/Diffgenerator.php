<?php
namespace DiffGenerator;
use Docopt;
function run()
{
    $doc = <<<'DOCOPT'
        Example of program which uses [options] shortcut in pattern.
        Usage:
            gendiff (-h | --help)
            gendiff --version
        Options:
            -h --help                Show this help message and exit
             --version               Show version.
DOCOPT;

    $args = Docopt::handle($doc, array('version' => 'Naval Fate 2.0'));
}

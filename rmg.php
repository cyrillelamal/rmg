#!/usr/bin/php
<?php

// config
const LONG_OPTIONS = [
    'glob:',
    'start:',
    'verbose',
];
const SHORT_OPTIONS = 'g:s:v';
const DEFAULT_GLOB = '';
const DEFAULT_START = './';
const DEFAULT_IS_VERBOSE = false;
const HANDLERS = [ // functors
    'unlink',
];

// - //
main();
// - //

function main()
{
    $glob = get_glob();
    $start = get_start();

    if ($glob === '') {
        stderr('Glob is not defined' . PHP_EOL);
        exit(1);
    }
    if (!is_dir($start)) {
        stderr('Not a directory' . PHP_EOL);
        exit(1);
    }

    $handlers = HANDLERS;
    if (is_verbose()) {
        array_unshift($handlers, function (string $data) {
            echo $data . PHP_EOL;
        });
    }

    walk(realpath($start), $glob, $handlers);

    exit(0);
}

function walk(string $dir, string $pattern, array $handlers = HANDLERS) // recursive
{
    $old = $dir;

    chdir($dir);

    // apply handlers
    foreach (glob($pattern) as $match) {
        foreach ($handlers as $handler) {
            call_user_func($handler, realpath($match));
        }
    }

    // continue walking
    foreach (scandir($dir) as $file) {
        $filename = $dir . DIRECTORY_SEPARATOR . $file; // absolute path

        if (is_dir($filename)) {
            if ($file === '.' || $file === '..') {
                continue;
            } else {
                walk($filename, $pattern, $handlers);
            }
        }
    }

    chdir($old);
}

function get_glob(): string
{
    $options = getopt(SHORT_OPTIONS, LONG_OPTIONS);

    return trim($options['g'] ?? $options['glob'] ?? DEFAULT_GLOB);
}

function get_start(): string
{
    $options = getopt(SHORT_OPTIONS, LONG_OPTIONS);

    return trim($options['s'] ?? $options['start'] ?? DEFAULT_START);
}

function is_verbose(): bool
{
    $options = getopt(SHORT_OPTIONS, LONG_OPTIONS);

    return isset($options['v']) || isset($options['verbose']) || DEFAULT_IS_VERBOSE;
}

function stderr(string $data)
{
    $h = STDERR;
    fputs($h, $data);
    fclose($h);
}

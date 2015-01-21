<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

//Alias for Doctrine Console

(@include_once __DIR__ . '/../vendor/autoload.php') || @include_once __DIR__ . '/../../../autoload.php';

$commands = [
    'create' => ['orm:schema-tool:create'],
    'update' => ['orm:schema-tool:update', '--force'],
    'drop'   => ['orm:schema-tool:drop', '--force'],
];

if (!isset($argv[1]) || !isset($commands[$argv[1]])) {
    echo 'Available commands: ' . join(', ', array_keys($commands)) . PHP_EOL;
    exit(1);
}

$_SERVER['argv'] = array_merge([$argv[0]], $commands[$argv[1]]);

\Doctrine\ORM\Tools\Console\ConsoleRunner::run(
    require __DIR__ . '/../config/cli-config.php',
    []
);

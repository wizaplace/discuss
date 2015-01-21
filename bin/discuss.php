<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

//Alias for Doctrine Console

(@include_once __DIR__ . '/../vendor/autoload.php') || @include_once __DIR__ . '/../../../autoload.php';

\Doctrine\ORM\Tools\Console\ConsoleRunner::run(
    require __DIR__ . '/../config/cli-config.php',
    []
);

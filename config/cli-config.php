<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

$file  = getcwd() . '/config/discuss.config.php';
if( !is_readable($file)) {
    echo "Missing file [$file]." . PHP_EOL;
    exit(1);
}

$client = new Wizacha\Discuss\Client(include($file));

return $client->getConsoleHelpers();

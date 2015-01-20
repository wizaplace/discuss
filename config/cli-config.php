<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */


$params = include(__DIR__ . '/local.php');
$client = new Wizacha\Discuss\Client($params, true);

return $client->getConsoleHelpers();

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

class ClientWithHelpers extends Wizacha\Discuss\Client
{
    public function getConsoleHelpers()
    {
        return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($this->getEntityManager());
    }
}
$client = new ClientWithHelpers(include($file), true);

return $client->getConsoleHelpers();

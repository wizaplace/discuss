<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Tests;


/**
 * Class ClientTest working in memory
 */
class Client extends \Wizacha\Discuss\Client
{
    public function __construct()
    {
        parent::__construct(
            [
                'user'     => '',
                'password' => '',
                'host'     => '',
                'dbname'   => '',
                'driver'   => 'pdo_sqlite',
                'memory'   => true,
            ],
            true
        );
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->_entityManager);
        $tool->createSchema($this->_entityManager->getMetadataFactory()->getAllMetadata());
    }
}
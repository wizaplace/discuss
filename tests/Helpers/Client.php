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
    static public function getDefaultConfig()
    {
        return [
            'user'     => '',
            'password' => '',
            'host'     => '',
            'dbname'   => '',
            'driver'   => 'pdo_sqlite',
            'memory'   => true,
        ];
    }

    public function __construct(array $config = null)
    {
        parent::__construct(
            $config ? : self::getDefaultConfig(),
            true
        );
        $em   = $this->getEntityManager();
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());
    }
}
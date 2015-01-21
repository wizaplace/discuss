<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

/**
 * Object to manage the connection with the Discuss service.
 *
 * @package Wizacha\Discuss
 */
class Client
{
    /**
     * @var EntityManager
     */
    protected $_entityManager;

    /**
     * @param array $params Doctrine connection parameters
     * @param bool  $isDevMode
     * @throws \Doctrine\ORM\ORMException
     */
    public function __construct($params, $isDevMode = false)
    {
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/Entity'], $isDevMode);
        $this->_entityManager = EntityManager::create($params, $config);
    }
}

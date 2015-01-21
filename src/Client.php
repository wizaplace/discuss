<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Wizacha\Discuss\Repository\MessageRepository;

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
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $_messageRepo;

    /**
     * @param array $params Doctrine connection parameters
     * @param bool  $isDevMode
     * @throws \Doctrine\ORM\ORMException
     */
    public function __construct(array $params, $isDevMode = false)
    {
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/Entity'], $isDevMode);
        $this->_entityManager = EntityManager::create($params, $config);

        $this->_messageRepo = new MessageRepository($this->_entityManager);
    }

    /**
     * @return  \Wizacha\Discuss\Repository\MessageRepository
     */
    public function getMessageRepository()
    {
        return $this->_messageRepo;
    }
}

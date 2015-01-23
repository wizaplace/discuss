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
use Wizacha\Discuss\Repository\DiscussionRepository;

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
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $_discussionRepo;

    /**
     * @param array $params Doctrine connection parameters
     * @param bool  $isDevMode
     * @throws \Doctrine\ORM\ORMException
     */
    public function __construct(array $params, $isDevMode = false)
    {
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/Entity'], $isDevMode);
        $this->_entityManager = EntityManager::create($params, $config);

        $this->_discussionRepo = new DiscussionRepository($this->_entityManager);
        $this->_messageRepo    = new MessageRepository($this->_entityManager, $this->_discussionRepo);
    }

    /**
     * @return  \Wizacha\Discuss\Repository\MessageRepository
     */
    public function getMessageRepository()
    {
        return $this->_messageRepo;
    }

    /**
     * @return  \Wizacha\Discuss\Repository\DiscussionRepository
     */
    public function getDiscussionRepository()
    {
        return $this->_discussionRepo;
    }
}

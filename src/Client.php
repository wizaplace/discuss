<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Wizacha\Discuss\Internal\EntityManagerAware;
use Wizacha\Discuss\Repository\MessageRepository;
use Wizacha\Discuss\Repository\DiscussionRepository;

/**
 * Object to manage the connection with the Discuss service.
 *
 * @package Wizacha\Discuss
 */
class Client extends EntityManagerAware
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $_messageRepo;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $_discussionRepo;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $_dispatcher;

    /**
     * @param array $params Doctrine connection parameters.
     * Optionally you can use these parameters:
     *  * EventDispatcherInterface event_dispatcher The dispatcher to use
     * @param bool  $isDevMode
     * @throws \Doctrine\ORM\ORMException
     */
    public function __construct(array $params, $isDevMode = false)
    {
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/Entity'], $isDevMode);
        $em = EntityManager::create($params, $config);
        parent::__construct($em);

        $this->_discussionRepo = new DiscussionRepository($this);
        $this->_messageRepo    = new MessageRepository($this);

        if (isset($params['event_dispatcher']) && $params['event_dispatcher'] instanceof EventDispatcherInterface) {
            $this->_dispatcher = $params['event_dispatcher'];
        } else {
            $this->_dispatcher = new EventDispatcher();
        }
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

    /**
     * @return EventDispatcher
     */
    public function getEventDispatcher()
    {
        return $this->_dispatcher;
    }
}

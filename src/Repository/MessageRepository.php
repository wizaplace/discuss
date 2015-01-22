<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Repository;


use Doctrine\ORM\EntityManager;
use Wizacha\Discuss\Entity\Message;
use Wizacha\Discuss\Entity\MessageInterface;

/**
 * Class MessageRepository
 * @package Wizacha\Discuss\Repository
 */
class MessageRepository
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $_repo;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->_em         = $entityManager;
        $this->_repo = $entityManager->getRepository('\Wizacha\Discuss\Entity\Message');
    }

    /**
     * @param integer $message_id If null, a new instance is created
     * @return \Wizacha\Discuss\Entity\MessageInterface | null
     * @throws \Exception
     */
    public function get($message_id = null)
    {
        return
            is_null($message_id) ?
                new Message()
                : $this->_repo->find($message_id);
    }

    /**
     * @param \Wizacha\Discuss\Entity\MessageInterface $message
     * @return int
     */
    public function save(MessageInterface $message)
    {
        $this->_em->persist($message);
        $this->_em->flush();
        return $message->getId();
    }
}

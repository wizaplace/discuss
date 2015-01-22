<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Repository;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
     * @param integer $message_id
     * @return \Wizacha\Discuss\Entity\MessageInterface | null
     * @throws \Exception
     */
    public function get($message_id)
    {
        return $this->_repo->find($message_id);
    }

    /**
     * @return \Wizacha\Discuss\Entity\MessageInterface
     */
    public function create()
    {
        return new Message();
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

    /**
     * @param integer $discussion_id
     * @param integer $nb_per_page
     * @param integer $page Page index, starting at 0
     * @return \Countable,\Traversable
     */
    public function getByDiscussion($discussion_id, $nb_per_page = null, $page = null)
    {
        $qb   = $this->_repo->createQueryBuilder('m');
        $expr = $qb->expr();
        $qb->where(
            $expr->eq('m.discussion', ':discussion_id')
        )->setParameters([
            'discussion_id' => $discussion_id
        ]);

        if ($nb_per_page > 0) {
            $qb->setMaxResults($nb_per_page);
            if ($page > 0) {
                $qb->setFirstResult($page * $nb_per_page);
            }
        }

        return new Paginator($qb);
    }
}

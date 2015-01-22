<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Repository;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Wizacha\Discuss\Entity\Discussion;
use Wizacha\Discuss\Entity\DiscussionInterface;

/**
 * Class DiscussionRepository
 * @package Wizacha\Discuss\Repository
 */
class DiscussionRepository
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
        $this->_em   = $entityManager;
        $this->_repo = $entityManager->getRepository('\Wizacha\Discuss\Entity\Discussion');
    }

    /**
     * @param integer $discussion_id If null, a new instance is created
     * @return \Wizacha\Discuss\Entity\DiscussionInterface | null
     * @throws \Exception
     */
    public function get($discussion_id = null)
    {
        return
            is_null($discussion_id) ?
                new Discussion()
                : $this->_repo->find($discussion_id);
    }

    /**
     * @param \Wizacha\Discuss\Entity\DiscussionInterface $discussion
     * @return int
     */
    public function save(DiscussionInterface $discussion)
    {
        $this->_em->persist($discussion);
        $this->_em->flush();
        return $discussion->getId();
    }

    /**
     * Get displayed discussions for a specific user
     *
     * @param integer $user_id
     * @param integer $nb_per_page
     * @param integer $page Page index starting at 0
     * @return \Countable,\Traversable
     */
    public function getByUser($user_id, $nb_per_page = null, $page = null)
    {
        $qb   = $this->_repo->createQueryBuilder('d');
        $expr = $qb->expr();
        $qb->where(
            $expr->orX(
                $expr->andX(
                    $expr->eq('d.initiator', ':user_id'),
                    $expr->eq('d.status_initiator', ':status')
                ),
                $expr->andX(
                    $expr->eq('d.recipient', ':user_id'),
                    $expr->eq('d.status_recipient', ':status')
                )
            )
        )->setParameters([
            'user_id' => $user_id,
            'status'=> Discussion\Status::DISPLAYED
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

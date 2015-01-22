<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Repository;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
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
     * @param integer $discussion_id
     * @return \Wizacha\Discuss\Entity\DiscussionInterface | null
     * @throws \Exception
     */
    public function get($discussion_id)
    {
        return $this->_repo->find($discussion_id);
    }

    /**
     * @return \Wizacha\Discuss\Entity\DiscussionInterface
     */
    public function create()
    {
        return new Discussion();
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
        $qb   = $this->_repo->createQueryBuilder('Discussion');
        $this->andWhereDiscussionUserIs($qb, $user_id);

        if ($nb_per_page > 0) {
            $qb->setMaxResults($nb_per_page);
            if ($page > 0) {
                $qb->setFirstResult($page * $nb_per_page);
            }
        }

        return new Paginator($qb);
    }


    /**
     * **INTERNAL USE ONLY**
     * Modify a query to filter visible discussion of a user
     *
     * @param QueryBuilder $queryBuilder The table must be named 'Discussion'
     * @param integer $user_id
     * @return QueryBuilder
     */
    public function andWhereDiscussionUserIs(QueryBuilder $queryBuilder, $user_id)
    {
        $expr = $queryBuilder->expr();
        return $queryBuilder->andWhere(
            $expr->orX(
                $expr->andX(
                    $expr->eq('Discussion.initiator', ':user_id'),
                    $expr->eq('Discussion.status_initiator', ':status')
                ),
                $expr->andX(
                    $expr->eq('Discussion.recipient', ':user_id'),
                    $expr->eq('Discussion.status_recipient', ':status')
                )
            )
        )->setParameter('user_id', $user_id)
        ->setParameter('status', Discussion\Status::DISPLAYED)
        ;
    }
}

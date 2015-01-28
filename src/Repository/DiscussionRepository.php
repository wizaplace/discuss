<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Repository;


use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Wizacha\Discuss\Client;
use Wizacha\Discuss\Entity\Discussion;
use Wizacha\Discuss\Entity\DiscussionInterface;
use Wizacha\Discuss\Entity\Discussion\Status;
use Wizacha\Discuss\Internal\EntityManagerAware;

/**
 * Class DiscussionRepository
 * @package Wizacha\Discuss\Repository
 */
class DiscussionRepository extends EntityManagerAware
{
    /**
     * @var \Wizacha\Discuss\Client
     */
    protected $_client;

    /**
     * @param \Wizacha\Discuss\Client $client
     */
    public function __construct(Client $client)
    {
        $this->_client = $client;
        parent::__construct($client->getEntityManager());
    }

    private function _getRepo()
    {
        return $this->getEntityManager()->getRepository('\Wizacha\Discuss\Entity\Discussion');
    }

    /**
     * @param integer $discussion_id
     * @return \Wizacha\Discuss\Entity\DiscussionInterface | null
     * @throws \Exception
     */
    public function get($discussion_id)
    {
        return $this->_getRepo()->find($discussion_id);
    }

    /**
     * Gets a discussion ONLY if including a specific user
     * @param integer $discussion_id
     * @param integer $user_id
     * @return \Wizacha\Discuss\Entity\DiscussionInterface | null
     * @throws \Exception
     */
    public function getIfUser($discussion_id, $user_id)
    {
        $qb   = $this->_repo->createQueryBuilder('Discussion');
        $expr       = $qb->expr();

        return $qb
            ->where($expr->eq('Discussion.id', ':discussion_id'))
            ->andWhere(
                $expr->orX(
                    $expr->eq('Discussion.initiator', ':user_id'),
                    $expr->eq('Discussion.recipient', ':user_id')
                )
            )->setParameters(
                [
                    'discussion_id' => $discussion_id,
                    'user_id'       => $user_id,
                ]
            )
        ->getQuery()->getOneOrNullResult();
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
        $em = $this->getEntityManager();
        $em->persist($discussion);
        $em->flush();
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
        return $this->getAll($user_id, new Status(Status::DISPLAYED), $nb_per_page, $page);
    }

    /**
     * Allow to retrieve all discussion, with optionnal filters.
     * For each filter, null value means *ALL*
     *
     * @param integer $user_id
     * @param Status $status
     * @param integer $nb_per_page
     * @param integer $page
     * @return Paginator
     */
    public function getAll($user_id = null, Status $status = null, $nb_per_page = null, $page = null)
    {
        $qb   = $this->_getRepo()->createQueryBuilder('Discussion');
        $this->andWhereDiscussionFilter($qb, $user_id, $status);

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
     * @param integer $user_id  If null, all users are selected
     * @param Discussion\Status $status If null, all status are selected
     * @return QueryBuilder
     */
    public function andWhereDiscussionFilter(QueryBuilder $queryBuilder, $user_id = null, Status $status = null)
    {
        $expr       = $queryBuilder->expr();
        $conditions = [];

        if($user_id) {
            $conditions[] = [
                'initiator' => $expr->eq('Discussion.initiator', ':user_id'),
                'recipient' => $expr->eq('Discussion.recipient', ':user_id'),
            ];
            $queryBuilder->setParameter('user_id', $user_id);
        }

        if($status) {
            $conditions[] = [
                'initiator' => $expr->eq('Discussion.status_initiator', ':status'),
                'recipient' => $expr->eq('Discussion.status_recipient', ':status'),
            ];
            $queryBuilder->setParameter('status', $status);
        }

        switch(count($conditions)) {
            case 1:
                $queryBuilder->andWhere(
                    $expr->orX($conditions[0]['initiator'], $conditions[0]['recipient'])
                );
                break;
            case 2:
                $queryBuilder->andWhere(
                    $expr->orX(
                        $expr->andX($conditions[0]['initiator'], $conditions[1]['initiator']),
                        $expr->andX($conditions[0]['recipient'], $conditions[1]['recipient'])
                    )
                );
                break;
        }
        return $queryBuilder;
    }
}

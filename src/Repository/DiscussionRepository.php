<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Repository;


use Doctrine\ORM\Query\Expr\Join;
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
        $qb   = $this->_getRepo()->createQueryBuilder('Discussion');
        $expr = $qb->expr();

        return $qb
            ->where($expr->eq('Discussion.id', ':discussion_id'))
            ->join(
                'Discussion.users',
                'DiscussionUser',
                Join::WITH,
                $expr->eq('DiscussionUser.user_id', ':user_id')
            )
            ->setParameters(
                [
                    'discussion_id' => $discussion_id,
                    'user_id'       => $user_id,
                ]
            )
        ->getQuery()->getOneOrNullResult();
    }

    public function getByCompanyIdAndUser(int $companyId, int $userId) {
        $qb   = $this->_getRepo()->createQueryBuilder('Discussion');
        $expr = $qb->expr();

        $query = $qb
            ->join(
                'Discussion.meta_data',
                'MetaData'
            )
            -> join(
                'Discussion.users',
                'DiscussionUser'
            )
            ->where($expr->andX($expr->eq('MetaData.name', ':name'),$expr->eq('MetaData.value', ':company_id')))
            ->orWhere($expr->andX($expr->eq('DiscussionUser.user_id', ':user_id'), $expr->eq('DiscussionUser.status', ':status')))
            ->setParameters(
                [
                    'name'       => 'company_id',
                    'company_id' => $companyId,
                    'user_id' => $userId,
                    'status' =>  new Status(Status::DISPLAYED)
                ]
            )
            ->getQuery();

        return new Paginator($query);
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
     *
     * @return Paginator
     */
    public function getAll($user_id = null, Status $status = null, $nb_per_page = null, $page = null)
    {
        $qb = $this->_getRepo()->createQueryBuilder('Discussion');

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
     * Allow to retrieve all discussion, with optionnal filters
     * ordered by discussion last message date DESC
     * For each filter, null value means *ALL*
     *
     * @param integer $user_id
     * @param Status $status
     * @param integer $nb_per_page
     * @param integer $page
     *
     * @return Paginator
     */
    public function getAllOrderedByMessageSendDate(
        int $user_id = null,
        Status $status = null,
        int $nb_per_page = null,
        int $page = null
    )
    {
        $qb = $this->_getRepo()->createQueryBuilder('Discussion');

        $qb
            ->select('Discussion')
            ->addSelect('Message')
            ->join('Discussion.messages', 'Message')
            ->orderBy('Message.send_date', 'DESC')
        ;

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
            $conditions[] = $expr->eq('DiscussionUser.user_id', ':user_id');
            $queryBuilder->setParameter('user_id', $user_id);
        }

        if($status) {
            $conditions[] = $expr->eq('DiscussionUser.status', ':status');
            $queryBuilder->setParameter('status', $status);
        }

        if($conditions) {
            $queryBuilder->join(
                'Discussion.users',
                'DiscussionUser',
                Join::WITH,
                call_user_func_array([$expr, 'andX'], $conditions)
            );
        }

        return $queryBuilder;
    }
}

<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Repository;


use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Wizacha\Discuss\Client;
use Wizacha\Discuss\DiscussEvents;
use Wizacha\Discuss\Entity\Discussion\Status;
use Wizacha\Discuss\Entity\Message;
use Wizacha\Discuss\Entity\MessageInterface;
use Wizacha\Discuss\Event\MessageEvent;
use Wizacha\Discuss\Internal\EntityManagerAware;

/**
 * Class MessageRepository
 * @package Wizacha\Discuss\Repository
 */
class MessageRepository extends EntityManagerAware
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
        return $this->getEntityManager()->getRepository('\Wizacha\Discuss\Entity\Message');
    }

    /**
     * @param integer $message_id
     * @return \Wizacha\Discuss\Entity\MessageInterface | null
     * @throws \Exception
     */
    public function get($message_id)
    {
        return $this->_getRepo()->find($message_id);
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
        $is_new = is_null($message->getId());
        if ($is_new) {
            $d            = $message->getDiscussion();
            $recipient_id = $d->getOtherUser($message->getAuthor());
            $d->setUserStatus($recipient_id, new Status(Status::DISPLAYED));
        }

        $em = $this->getEntityManager();
        $em->persist($message);
        $em->flush();

        if ($is_new) {
            $this->_client->getEventDispatcher()->dispatch(
                DiscussEvents::MESSAGE_NEW,
                new MessageEvent($message)
            );
        }
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
        $qb   = $this->_getRepo()->createQueryBuilder('m');
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

    /**
     * @param integer $user_id
     * @param integer $discussion_id If set, scope is limited to this discussion
     * @return int
     */
    public function getUnreadCount($user_id, $discussion_id = null)
    {
        $repo = $this->getEntityManager()->getRepository('\Wizacha\Discuss\Internal\Entity\MessageRecipient');
        $qb   = $repo->createQueryBuilder('r');
        $expr = $qb->expr();
        $qb->select($expr->count('r.id'))
            ->where($expr->eq('r.user_id', ':user_id'))
            ->andWhere($expr->isNull('r.read_date'))
            ->join(
                'r.message',
                'm',
                Join::WITH
            )
            ->join('m.discussion',
                'Discussion',
                Join::WITH
            )
            ->setParameters([
                'user_id' => $user_id,
            ])
        ;
        if($discussion_id > 0) {
            $qb->andWhere($expr->eq('m.discussion', ':discussion_id'))
                ->setParameter('discussion_id', $discussion_id)
            ;
        }
        $qb = $this->_client->getDiscussionRepository()->andWhereDiscussionFilter(
            $qb,
            $user_id,
            new Status(Status::DISPLAYED)
        );

        return (integer)$qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param integer $discussion_id
     * @return Message|null
     */
    public function getLastOfDiscussion($discussion_id)
    {
        $qb   = $this->_getRepo()->createQueryBuilder('m');
        $expr = $qb->expr();
        return $qb->where(
            $expr->eq('m.discussion', ':discussion_id')
        )
            ->setParameter('discussion_id', $discussion_id)
            ->orderBy('m.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

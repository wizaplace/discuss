<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Entity;


use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Wizacha\Discuss\Entity\Discussion\Status;

/**
 * Class Discussion
 *
 * @package Wizacha\Discuss\Entity
 * @Entity()
 */
class Discussion implements DiscussionInterface
{
    /**
     * @var int
     * @Id()
     * @Column(type="integer")
     * @GeneratedValue()
     */
    protected $id;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $initiator;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $recipient;

    /**
     * @var Discussion/Status
     * @Column(type="string", length=1)
     */
    protected $status_initiator = Status::OPEN;

    /**
     * @var Discussion/Status
     * @Column(type="string", length=1)
     */
    protected $status_recipient = Status::OPEN;

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function setInitiator($initiator)
    {
        $this->initiator = $initiator;
    }

    /**
     * @inheritdoc
     */
    public function getInitiator()
    {
        return $this->initiator;
    }

    /**
     * @inheritdoc
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @inheritdoc
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @var $status_initiator
     */
    private function setStatusInitiator($status_initiator)
    {
        $this->status_initiator = $status_initiator;
    }

    /**
     * @inheritdoc
     */
    public function getStatusInitiator()
    {
        return $this->status_initiator;
    }

    /**
     * @var int $status_recipient
     */
    private function setStatusRecipient($status_recipient)
    {
        $this->status_recipient = $status_recipient;
    }

    /**
     * @inheritdoc
     */
    public function getStatusRecipient()
    {
        return $this->status_recipient;
    }

    /**
     * @inheritdoc
     */
    public function closeDiscussion()
    {
        $this->setStatusRecipient(Status::CLOSED);
        $this->setStatusInitiator(Status::CLOSED);
    }

    /**
     * @inheritdoc
     */
    public function hideDiscussion($user_id)
    {
        if ($this->getStatusRecipient() !== Status::CLOSED) {
            return false;
        }

        if ($this->getRecipient() == $user_id) {
            $this->setStatusRecipient(Status::HIDDEN);
        } elseif ($this->getInitiator() == $user_id) {
            $this->setStatusInitiator(Status::HIDDEN);
        } else {
            return false;
        }

        return true;
    }
}

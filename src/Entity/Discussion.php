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
    protected $status_initiator;

    /**
     * @var Discussion/Status
     * @Column(type="string", length=1)
     */
    protected $status_recipient;

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
     * @inheritdoc
     */
    public function setStatusInitiator($status_initiator)
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
     * @inheritdoc
     */
    public function setStatusRecipient($status_recipient)
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
}

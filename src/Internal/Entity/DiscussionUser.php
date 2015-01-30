<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Internal\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Wizacha\Discuss\Entity\Discussion\Status;
use Wizacha\Discuss\Entity\DiscussionInterface;

/**
 * Class DiscussionUser
 * @package Wizacha\Discuss\Internal\Entity
 * @Entity()
 */
class DiscussionUser
{
    /**
     * @Id()
     * @Column(type="integer")
     * @GeneratedValue()
     * @var integer
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="\Wizacha\Discuss\Entity\Discussion", inversedBy="users")
     * @JoinColumn(name="discussion_id", referencedColumnName="id")
     * @var DiscussionInterface
     */
    protected $discussion;

    /**
     * @Column(type="integer")
     * @var int
     */
    protected $user_id;

    /**
     * @Column(type="string", length=1)
     * @var Status
     */
    protected $status;

    /**
     * @Column(type="boolean")
     * @var bool
     */
    protected $is_initiator;

    /**
     * @param DiscussionInterface $discussion
     * @param int $user_id
     * @param Status $status
     * @param bool $is_initiator
     */
    public function __construct(DiscussionInterface $discussion, $user_id, Status $status, $is_initiator)
    {
        $this->discussion   = $discussion;
        $this->user_id      = (integer)$user_id;
        $this->status       = $status;
        $this->is_initiator = (bool)$is_initiator;
    }

    /**
     * @return boolean
     */
    public function isInitiator()
    {
        return $this->is_initiator;
    }

    /**
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param Status $status
     * @return $this
     */
    public function setStatus(Status $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }
}

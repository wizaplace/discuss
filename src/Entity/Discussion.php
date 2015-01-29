<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Wizacha\Discuss\Internal\Entity\MetaData;
use Wizacha\Discuss\Entity\Discussion\Status;
use Wizacha\Discuss\Internal\Entity\DiscussionUser;

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
     * @var DiscussionUser[]
     * @OneToMany(targetEntity="\Wizacha\Discuss\Internal\Entity\DiscussionUser", mappedBy="discussion", indexBy="user_id", cascade={"ALL"})
     */
    protected $users;

    /**
     * @var bool
     * @Column(type="boolean")
     */
    protected $open = true;

    /**
     * @var MetaData[]
     * @OneToMany(targetEntity="\Wizacha\Discuss\Internal\Entity\MetaData", mappedBy="discussion", indexBy="key", cascade={"ALL"})
     */
    protected $meta_data = [];

    public function __construct()
    {
        $this->users = new ArrayCollection;
    }

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
        $this->users = $this->users->filter(function($user) {
            return !$user->isInitiator();
        });
        $this->users[$initiator] = new DiscussionUser($this, $initiator, new Status(Status::DISPLAYED), true);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUsers()
    {
        return $this->users->getKeys();
    }

    /**
     * @inheritdoc
     */
    public function getOtherUser($user_id)
    {
        foreach($this->users as $other_id => $user) {
            if($other_id != $user_id) {
                return $other_id;
            }
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getInitiator()
    {
        foreach ($this->users as $user_id => $user) {
            if ($user->isInitiator()) {
                return $user_id;
            }
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function setRecipient($recipient)
    {
        $this->users = $this->users->filter(function ($user) {
            return $user->isInitiator();
        });
        $this->users[$recipient] = new DiscussionUser($this, $recipient, new Status(Status::DISPLAYED), false);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRecipient()
    {
        foreach ($this->users as $user_id => $user) {
            if (!$user->isInitiator()) {
                return $user_id;
            }
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getStatusInitiator()
    {
        return $this->users[$this->getInitiator()]->getStatus();
    }

    /**
     * @inheritdoc
     */
    public function setOpen($open)
    {
        $this->open = $open;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * @inheritdoc
     */
    public function getStatusRecipient()
    {
        return $this->users[$this->getRecipient()]->getStatus();
    }

    /**
     * @inheritdoc
     */
    public function hideDiscussion($user_id)
    {
        if (isset($this->users[$user_id])) {
            $this->users[$user_id]->setStatus(new Status(Status::HIDDEN));
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMetaData($key)
    {
        return isset($this->meta_data[$key]) ? $this->meta_data[$key]->getValue() : null;
    }

    /**
     * @inheritdoc
     */
    public function setMetaData($key, $value)
    {
        $this->meta_data[$key] = new MetaData($this, $key, $value);
        return $this;
    }
}

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
use Doctrine\ORM\Mapping\OneToMany;
use Wizacha\Discuss\Entity\Discussion\MetaData;
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
    protected $status_initiator = Status::DISPLAYED;

    /**
     * @var Discussion/Status
     * @Column(type="string", length=1)
     */
    protected $status_recipient = Status::DISPLAYED;

    /**
     * @var bool
     * @Column(type="boolean")
     */
    protected $open = true;

    /**
     * @var MetaData[]
     * @OneToMany(targetEntity="\Wizacha\Discuss\Entity\Discussion\MetaData", mappedBy="discussion", indexBy="key", cascade={"ALL"})
     */
    protected $meta_data = [];

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
        return $this;
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
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param Status $status_initiator
     * @return $this
     */
    private function setStatusInitiator(Status $status_initiator)
    {
        $this->status_initiator = $status_initiator;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getStatusInitiator()
    {
        return new Status($this->status_initiator);
    }

    /**
     * @param Status $status_recipient
     * @return $this
     */
    private function setStatusRecipient(Status $status_recipient)
    {
        $this->status_recipient = $status_recipient;
        return $this;
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
        return new Status($this->status_recipient);
    }

    /**
     * @inheritdoc
     */
    public function hideDiscussion($user_id)
    {
        if ($this->getRecipient() == $user_id) {
            $this->setStatusRecipient(new Status(Status::HIDDEN));
        } elseif ($this->getInitiator() == $user_id) {
            $this->setStatusInitiator(new Status(Status::HIDDEN));
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

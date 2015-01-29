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
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Wizacha\Discuss\Internal\Entity\MessageRecipient;

/**
 * Class Message
 *
 * @package Wizacha\Discuss\Entity
 * @Entity()
 */
class Message implements MessageInterface
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
    protected $author;

    /**
     * @var \DateTime
     * @Column(type="datetime")
     */
    protected $send_date;

    /**
     * @var string
     * @Column(type="text")
     */
    protected $content;

    /**
     * @ManyToOne(targetEntity="Discussion", cascade={"all"})
     * @JoinColumn(nullable=false)
     */
    protected $discussion;

    /**
     * @OneToMany(targetEntity="\Wizacha\Discuss\Internal\Entity\MessageRecipient", mappedBy="message", indexBy="user_id", cascade={"ALL"})
     * @var MessageRecipient[]
     */
    protected $recipients;

    public function __construct()
    {
        $this->recipients = new ArrayCollection;
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
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this->_updateRecipients();
    }

    /**
     * @inheritdoc
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @inheritdoc
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @inheritdoc
     */
    public function setDiscussion(DiscussionInterface $discussion)
    {
        $this->discussion = $discussion;
        return $this->_updateRecipients();
    }

    /**
     * @inheritdoc
     */
    public function getDiscussion()
    {
        return $this->discussion;
    }

    /**
     * @inheritdoc
     */
    public function setReadDate($read_date)
    {
        if (!$this->recipients->isEmpty()) {
            $this->recipients->first()->setReadDate($read_date);
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getReadDate()
    {
        return $this->recipients->isEmpty() ?
            null
            : $this->recipients->first()->getReadDate();
    }

    /**
     * @inheritdoc
     */
    public function isRead()
    {
        return $this->recipients->isEmpty() ?
            false
            : $this->recipients->first()->isRead();
    }

    /**
     * @inheritdoc
     */
    public function setAsRead()
    {
        if (!$this->recipients->isEmpty()) {
            $this->recipients->first()->setAsRead();
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setSendDate($send_date)
    {
        $this->send_date = $send_date;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSendDate()
    {
        return $this->send_date;
    }

    /**
     * @return $this
     */
    private function _updateRecipients()
    {
        if( is_null($this->author) || is_null($this->discussion)) {
            $this->recipients->clear();
        } else {
            $recipient_id     = $this->discussion->getOtherUser($this->author);
            $this->recipients[$recipient_id] = new MessageRecipient($this, $recipient_id);
        }
        return $this;
    }
}

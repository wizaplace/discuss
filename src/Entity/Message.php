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
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

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
     * @var \DateTime
     * @Column(type="datetime", nullable=true)
     * @null
     */
    protected $read_date;

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
        return $this;
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
        return $this;
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
        $this->read_date = $read_date;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getReadDate()
    {
        return $this->read_date;
    }

    /**
     * @inheritdoc
     */
    public function isRead()
    {
        return null !== $this->read_date;
    }

    /**
     * @inheritdoc
     */
    public function setAsRead()
    {
        $this->read_date = new \DateTime();
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
}

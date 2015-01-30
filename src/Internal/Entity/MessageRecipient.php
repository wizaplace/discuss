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
use Doctrine\ORM\Mapping\ManyToOne;
use Wizacha\Discuss\Entity\MessageInterface;

/**
 * Class MessageRecipient
 * @package Wizacha\Discuss\Internal\Entity
 * @Entity()
 */
class MessageRecipient
{
    /**
     * @Id()
     * @Column(type="integer")
     * @GeneratedValue()
     * @var integer
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="\Wizacha\Discuss\Entity\Message", inversedBy="recipients")
     * @var MessageInterface
     */
    protected $message;

    /**
     * @Column(type="integer")
     * @var int
     */
    protected $user_id;

    /**
     * @Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $read_date;

    /**
     * @param MessageInterface $message
     * @param int $recipient_id
     */
    public function __construct(MessageInterface $message, $recipient_id)
    {
        $this->message = $message;
        $this->user_id = (int)$recipient_id;
    }

    /**
     * @return int
     */
    public function getRecipientId()
    {
        return $this->user_id;
    }

    /**
     * @return \DateTime
     */
    public function getReadDate()
    {
        return $this->read_date;
    }

    /**
     * @return bool
     */
    public function isRead()
    {
        return null !== $this->read_date;
    }

    /**
     * @param \DateTime $date
     * @return $this
     */
    public function setReadDate(\DateTime $date)
    {
        $this->read_date = $date;
        return $this;
    }

    /**
     * @return $this
     */
    public function setAsRead()
    {
        $this->read_date = new \DateTime();
        return $this;
    }
}

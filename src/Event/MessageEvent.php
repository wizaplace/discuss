<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Event;

use Symfony\Component\EventDispatcher\GenericEvent;
use Wizacha\Discuss\Entity\MessageInterface;

/**
 * Class MessageEvent
 * @package Wizacha\Discuss\Event
 */
class MessageEvent extends GenericEvent
{
    /**
     * @var MessageInterface
     */
    protected $_msg;

    /**
     * @param MessageInterface $msg
     */
    public function __construct(MessageInterface $msg)
    {
        $this->_msg = $msg;
    }

    /**
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->_msg;
    }
}

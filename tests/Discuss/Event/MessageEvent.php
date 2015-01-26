<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Event\tests\unit;

use mageekguy\atoum;
use Wizacha\Discuss\Event\MessageEvent as MessageEventTest;

class MessageEvent extends atoum\test
{
    public function test_messageIsReturned()
    {
        $msg   = new \mock\Wizacha\Discuss\Entity\MessageInterface;
        $event = new MessageEventTest($msg);
        $this->object($event->getMessage())->isIdenticalTo($msg);
    }
}

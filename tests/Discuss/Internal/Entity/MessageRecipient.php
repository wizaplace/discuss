<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Internal\Entity\tests\unit;

use mageekguy\atoum;
use Wizacha\Discuss\Internal\Entity\MessageRecipient as MessageRecipientTest;

class MessageRecipient extends atoum\test
{
    public function test_constructAndGet_succeed()
    {
        $msg = new \mock\Wizacha\Discuss\Entity\MessageInterface;
        $id  = 51;
        $r   = new MessageRecipientTest($msg, $id);

        $this
            ->integer($r->getRecipientId())->isIdenticalTo($id)
            ->variable($r->getReadDate())->isNull()
            ->boolean($r->isRead())->isFalse()
        ;
    }

    public function test_setReadDate_succeed()
    {
        $r    = new MessageRecipientTest(new \mock\Wizacha\Discuss\Entity\MessageInterface, 0);
        $date = new \DateTime();

        $this
            ->object($r->setReadDate($date))->isIdenticalTo($r)
            ->variable($r->getReadDate())->isIdenticalTo($date)
            ->boolean($r->isRead())->isTrue()
            ->boolean($date <= $r->setAsRead()->getReadDate())->isTrue()
            ->boolean($r->getReadDate() <= new \DateTime())->isTrue()
        ;
    }
}

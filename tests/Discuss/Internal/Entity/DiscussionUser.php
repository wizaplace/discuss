<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Internal\Entity\tests\unit;

use mageekguy\atoum;
use Wizacha\Discuss\Entity\Discussion\Status;
use Wizacha\Discuss\Internal\Entity\DiscussionUser as DiscussionUserTest;

class DiscussionUser extends atoum\test
{
    public function test_constructorAndGet_succeed()
    {
        $d       = new \mock\Wizacha\Discuss\Entity\DiscussionInterface();
        $user_id = 51;

        foreach(Status::toArray() as $status) {
            $status = new Status($status);
            foreach([true, false] as $is_initiator) {
                $u = new DiscussionUserTest($d, $user_id, $status, $is_initiator);
                $this
                    ->boolean($u->isInitiator())->isIdenticalTo($is_initiator)
                    ->integer($u->getUserId())->isIdenticalTo($user_id)
                    ->object($u->getStatus())->isIdenticalTo($status)
                ;
            }
        }
    }

    public function test_setStatus_succeed()
    {
        $u = new DiscussionUserTest(
            new \mock\Wizacha\Discuss\Entity\DiscussionInterface(),
            51,
            new Status(Status::DISPLAYED),
            true
        );

        $this
            ->string((string)$u->setStatus(new Status(Status::HIDDEN))->getStatus())
            ->isIdenticalTo(Status::HIDDEN)
        ;
    }
}

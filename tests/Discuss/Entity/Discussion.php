<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Entity\tests\unit;

use mageekguy\atoum;
use Wizacha\Discuss\Entity\Discussion as DiscussionTest;

class Discussion extends atoum\test
{
    public function test_getId_IsNullOnNewEntity()
    {
        $dsc = new DiscussionTest();
        $this->variable($dsc->getId())->isNull();
    }

    public function testInitiator()
    {
        $i = 1;

        $dsc = new DiscussionTest();
        $dsc->setInitiator($i);

        $this
            ->integer($dsc->getInitiator())
            ->isIdenticalTo($i);
    }

    public function testRecipient()
    {
        $r = 1;

        $dsc = new DiscussionTest();
        $dsc->setRecipient($r);

        $this
            ->integer($dsc->getRecipient())
            ->isIdenticalTo($r);
    }

    public function testOpen()
    {
        $d = new DiscussionTest();

        $this
            ->boolean($d->getOpen())
            ->isTrue();

        $d->setOpen(false);

        $this
            ->boolean($d->getOpen())
            ->isFalse();
    }

    public function testHideDiscussionWithNonExistentUserReturnFalse()
    {
        $d = new DiscussionTest();

        $d->setRecipient(1);
        $d->setInitiator(2);

        $this
            ->boolean($d->hideDiscussion(3))
            ->isFalse();
    }

    public function HideDiscussionWithExistentUserReturnTrueData_Provider()
    {
        return [
            [1, DiscussionTest\Status::HIDDEN, DiscussionTest\Status::DISPLAYED],
            [2, DiscussionTest\Status::DISPLAYED, DiscussionTest\Status::HIDDEN],
        ];
    }

    /**
     * @dataProvider HideDiscussionWithExistentUserReturnTrueData_Provider
     */
    public function testHideDiscussionWithExistentUserReturnTrue($user_id, $exp_recipient_status, $exp_initiator_status)
    {
        $d = new DiscussionTest();

        $d->setRecipient(1);
        $d->setInitiator(2);

        $this
            ->boolean($d->hideDiscussion($user_id))
            ->isTrue();

        $this
            ->string($d->getStatusRecipient())
            ->isEqualTo($exp_recipient_status);

        $this
            ->string($d->getStatusInitiator())
            ->isEqualTo($exp_initiator_status);
    }
}

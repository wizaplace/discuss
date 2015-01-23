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

        $this
            ->object($dsc->setInitiator($i))->isIdenticalTo($dsc)
            ->integer($dsc->getInitiator())
            ->isIdenticalTo($i);
    }

    public function testRecipient()
    {
        $r = 1;

        $dsc = new DiscussionTest();

        $this
            ->object($dsc->setRecipient($r))->isIdenticalTo($dsc)
            ->integer($dsc->getRecipient())
            ->isIdenticalTo($r);
    }

    public function testOpen()
    {
        $d = new DiscussionTest();

        $this
            ->boolean($d->getOpen())
            ->isTrue();

        $this
            ->object($d->setOpen(false))->isIdenticalTo($d)
            ->boolean($d->getOpen())
            ->isFalse();
    }

    public function testHideDiscussionWithNonExistentUserDoNothing()
    {
        $d = (new DiscussionTest())
            ->setRecipient(1)
            ->setInitiator(2)
        ;

        $this
            ->object($d->hideDiscussion(3))->isIdenticalTo($d)
            ->string((string)$d->getStatusRecipient())->isIdenticalTo(DiscussionTest\Status::DISPLAYED)
            ->string((string)$d->getStatusInitiator())->isIdenticalTo(DiscussionTest\Status::DISPLAYED)
        ;
    }

    public function HideDiscussionWithExistentUserSucceed_DataProvider()
    {
        return [
            [1, DiscussionTest\Status::HIDDEN, DiscussionTest\Status::DISPLAYED],
            [2, DiscussionTest\Status::DISPLAYED, DiscussionTest\Status::HIDDEN],
        ];
    }

    /**
     * @dataProvider HideDiscussionWithExistentUserSucceed_DataProvider
     */
    public function testHideDiscussionWithExistentUserSucced($user_id, $exp_recipient_status, $exp_initiator_status)
    {
        $d = (new DiscussionTest())
            ->setRecipient(1)
            ->setInitiator(2)
        ;

        $this
            ->object($d->hideDiscussion($user_id))
            ->isIdenticalTo($d);

        $this
            ->string($d->getStatusRecipient())
            ->isEqualTo($exp_recipient_status);

        $this
            ->string($d->getStatusInitiator())
            ->isEqualTo($exp_initiator_status);
    }
}

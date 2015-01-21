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

    public function testHideDiscussionWithExistentUserReturnTrue()
    {
        $d = new DiscussionTest();

        $d->setRecipient(1);
        $d->setInitiator(2);

        $this
            ->boolean($d->hideDiscussion(1))
            ->isTrue();

        $this
            ->string($d->getStatusRecipient())
            ->isEqualTo(DiscussionTest\Status::HIDDEN);

        $this
            ->string($d->getStatusInitiator())
            ->isEqualTo(DiscussionTest\Status::DISPLAYED);
    }
}

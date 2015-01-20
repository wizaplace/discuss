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

    public function testStatusInitiator()
    {
        $i = DiscussionTest\Status::HIDDEN;

        $dsc = new DiscussionTest();
        $dsc->setStatusInitiator($i);

        $this
            ->string($dsc->getStatusInitiator())
            ->isIdenticalTo($i);
    }

    public function testStatusRecipient()
    {
        $i = DiscussionTest\Status::CLOSED;

        $dsc = new DiscussionTest();
        $dsc->setStatusRecipient($i);

        $this
            ->string($dsc->getStatusRecipient())
            ->isIdenticalTo($i);
    }
}

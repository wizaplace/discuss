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
            ->variable($dsc->getInitiator())->isNull()
            ->object($dsc->setInitiator($i))->isIdenticalTo($dsc)
            ->integer($dsc->getInitiator())
            ->isIdenticalTo($i);
    }

    public function testRecipient()
    {
        $r = 1;

        $dsc = new DiscussionTest();

        $this
            ->variable($dsc->getRecipient())->isNull()
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
            [1, new DiscussionTest\Status(DiscussionTest\Status::HIDDEN), new DiscussionTest\Status(DiscussionTest\Status::DISPLAYED)],
            [2, new DiscussionTest\Status(DiscussionTest\Status::DISPLAYED), new DiscussionTest\Status(DiscussionTest\Status::HIDDEN)],
        ];
    }

    /**
     * @dataProvider HideDiscussionWithExistentUserSucceed_DataProvider
     */
    public function testHideDiscussionWithExistentUserSucceed($user_id, $exp_recipient_status, $exp_initiator_status)
    {
        $d = (new DiscussionTest())
            ->setRecipient(1)
            ->setInitiator(2)
        ;

        $this
            ->object($d->hideDiscussion($user_id))
            ->isIdenticalTo($d);

        $this
            ->object($d->getStatusRecipient())
            ->isEqualTo($exp_recipient_status);

        $this
            ->object($d->getStatusInitiator())
            ->isEqualTo($exp_initiator_status);
    }

    public function test_setGetMetaData_succeed()
    {
        $meta_data = [
            'key1'    => 'hello',
            'key2'    => 'world',
        ];
        $d = new DiscussionTest();

        foreach($meta_data as $existing_key => $value) {
            $this
                ->object($d->setMetaData($existing_key, $value))
                ->isIdenticalTo($d)
            ;
        }

        foreach($meta_data as $key => $value) {
            $this
                ->variable($d->getMetaData($key))
                ->isIdenticalTo($value)
            ;
        }

        $this
            ->variable($d->getMetaData('Unknown name'))
            ->isNull()
        ;

        reset($meta_data);
        $existing_key = key($meta_data);
        $new_value    = 'NEW';
        $this
            ->variable($d->getMetaData($existing_key))
                ->isIdenticalTo($meta_data[$existing_key])
            ->variable($d->setMetaData($existing_key, $new_value)->getMetaData($existing_key))
                ->isIdenticalTo($new_value)
        ;
    }

    public function test_getUsers_succeed()
    {
        $users = [2, 1];
        $d     = (new DiscussionTest)
            ->setInitiator($users[0])
            ->setRecipient($users[1])
        ;
        $this->array($d->getUsers())->isIdenticalTo($users);
    }

    public function test_getOtherUser()
    {
        $d = new DiscussionTest;
        $this
            ->variable($d->getOtherUser(1))->isNull()
        ;

        $d->setInitiator(2)->setRecipient(1);
        $this
            ->integer($d->getOtherUser(1))->isIdenticalTo(2)
            ->integer($d->getOtherUser(2))->isIdenticalTo(1)
            ->integer($d->getOtherUser(0))->isIdenticalTo(2)
        ;
    }
}

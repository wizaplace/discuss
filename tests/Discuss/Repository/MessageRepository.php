<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Repository\tests\unit;

use Wizacha\Discuss\Tests\Client;
use Wizacha\Discuss\Tests\RepositoryTest;

class MessageRepository extends RepositoryTest
{
    public function test_createSaveGet_succeed()
    {
        $repo = (new Client())->getMessageRepository();
        $msg = $repo->create();
        $this->object($msg)->isInstanceOf('\Wizacha\Discuss\Entity\MessageInterface');

        $msg_data = [
            'Author'    => 51,
            'Content'   => 'This is a Content',
            'SendDate'  => new \DateTime(),
            'Discussion'=> $this->createDiscussion(),
        ];
        $this->fillEntity($msg, $msg_data);

        $msg_id = $repo->save($msg);
        $this->integer($msg_id)->isGreaterThan(0);

        $msg = $repo->get($msg_id);
        $this->object($msg)->isInstanceOf('\Wizacha\Discuss\Entity\MessageInterface');

        $this->testEntityData($msg, $msg_data);
    }

    public function test_get_failIfNotExist()
    {
        $repo = (new Client())->getMessageRepository();
        $this->variable($repo->get(3))->isNull();
    }

    public function test_getByDiscussion_FilterSucceed()
    {
        $repo             = (new Client())->getMessageRepository();
        $all_disc         = [$this->createDiscussion(), $this->createDiscussion()];
        $expected_msg_ids = [[], []];
        foreach($all_disc as $key => $disc) {
            $expected_msg_ids[$key][] = $repo->save($this->createMessage($disc));
        }

        foreach($all_disc as $key => $disc) {
            $msg_ids = [];
            foreach($repo->getByDiscussion($disc->getId()) as $msg) {
                $msg_ids[] = $msg->getId();
            }
            sort($msg_ids);
            $this->array($msg_ids)->isIdenticalTo($expected_msg_ids[$key]);
        }
    }

    public function test_getByDiscussion_PaginationSucceed()
    {
        $repo = (new Client())->getMessageRepository();

        $d = $this->createDiscussion();
        for($i=0;$i<10;++$i) {
            $repo->save($this->createMessage($d));
        }

        $pager = $repo->getByDiscussion($d->getId());
        $this->object($pager)->isInstanceOf('\Countable')->isInstanceOf('\Traversable');
        //Retrieve All
        $this->object($pager)
            ->hasSize(10)
            ->array(iterator_to_array($pager))->hasSize(10)
        ;

        //Retrieve complete page
        $pager = $repo->getByDiscussion($d->getId(), 7);
        $this->object($pager)
            ->hasSize(10)
            ->array(iterator_to_array($pager))->hasSize(7)
        ;

        //Retrieve incomplete page
        $pager = $repo->getByDiscussion($d->getId(), 7, 1);
        $this->object($pager)
            ->hasSize(10)
            ->array(iterator_to_array($pager))->hasSize(3)
        ;

        //Retrieve empty page
        $pager = $repo->getByDiscussion($d->getId(), 7, 2);
        $this->object($pager)
            ->hasSize(10)
            ->array(iterator_to_array($pager))->hasSize(0)
        ;
    }

    public function test_getUnreadCount_succeed()
    {
        $repo        = (new Client())->getMessageRepository();
        $discussions = [
            $this->createDiscussion(),
            $this->createDiscussion(),
        ];
        $nb_discussion = count($discussions);

        $nb_unread_per_disc = 4;
        foreach($discussions as $d) {
            for($i=0; $i< $nb_discussion * $nb_unread_per_disc; ++$i) {
                $msg = $this->createMessage($d);
                if($i % $nb_discussion) {
                    $msg->setReadDate(new \DateTime());
                }
                $repo->save($msg);
            }
        }

        $this
            ->integer($repo->getUnreadCount(RepositoryTest::AUTHOR_ID))->isZero()
            ->integer($repo->getUnreadCount(RepositoryTest::RECIPIENT_ID))->isIdenticalTo($nb_unread_per_disc * $nb_discussion)
        ;
        $discussion_id = reset($discussions)->getId();
        $this
            ->integer($repo->getUnreadCount(RepositoryTest::AUTHOR_ID, $discussion_id))->isZero()
            ->integer($repo->getUnreadCount(RepositoryTest::RECIPIENT_ID, $discussion_id))->isIdenticalTo($nb_unread_per_disc)
        ;
        $unknown_id = 9999;
        $this
            ->integer($repo->getUnreadCount(RepositoryTest::AUTHOR_ID, $unknown_id))->isZero()
            ->integer($repo->getUnreadCount(RepositoryTest::RECIPIENT_ID, $unknown_id))->isZero()
        ;

    }
}

<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Repository\tests\unit;

use Wizacha\Discuss\DiscussEvents;
use Wizacha\Discuss\Entity\Discussion\Status;
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

    public function test_saveNewMessage_showDiscussionForMessageRecipient()
    {
        $d = $this->createDiscussion()
            ->setInitiator(1)->setUserStatus(1, new Status(Status::HIDDEN))
            ->setRecipient(2)->setUserStatus(2, new Status(Status::HIDDEN))
        ;
        $msg = $this->createMessage($d)
            ->setAuthor(2)
        ;

        $repo = (new Client())->getMessageRepository();
        $repo->save($msg);

        $this
            ->string((string)$d->getStatusInitiator())->isIdenticalTo(Status::DISPLAYED)
            ->string((string)$d->getStatusRecipient())->isIdenticalTo(Status::HIDDEN)
        ;

        $d->setUserStatus(2, new Status(Status::DISPLAYED));
        $this->string((string)$d->getStatusRecipient())->isIdenticalTo(Status::DISPLAYED);
        $repo->save($msg);
        $this->string((string)$d->getStatusRecipient())->isIdenticalTo(Status::DISPLAYED);
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
            for($i=0; $i< 2 * $nb_unread_per_disc; ++$i) {
                $msg = $this->createMessage($d);
                if($i % 2) {
                    $msg->setAsRead();
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

    public function test_getUnreadCount_discardHiddenDiscussion()
    {
        $client      = new Client();
        $repo        = $client->getMessageRepository();
        $discussion  = $this->createDiscussion();

        $nb_msg_per_user = 10;
        for($i=0; $i< $nb_msg_per_user; ++$i) {
            $msg = $this->createMessage($discussion);
            $repo->save($msg);

            $msg = $this->createMessage($discussion)
                ->setAuthor(RepositoryTest::RECIPIENT_ID)
            ;
            $repo->save($msg);
        }
        $client->getDiscussionRepository()
            ->save($discussion->hideDiscussion(RepositoryTest::RECIPIENT_ID));

        $this
            ->integer($repo->getUnreadCount(RepositoryTest::AUTHOR_ID))->isIdenticalTo($nb_msg_per_user)
            ->integer($repo->getUnreadCount(RepositoryTest::RECIPIENT_ID))->isZero()
        ;
        $this
            ->integer($repo->getUnreadCount(RepositoryTest::AUTHOR_ID, $discussion->getId()))->isIdenticalTo($nb_msg_per_user)
            ->integer($repo->getUnreadCount(RepositoryTest::RECIPIENT_ID, $discussion->getId()))->isZero()
        ;
    }

    public function test_getLastOfDiscussion_succeed()
    {
        $repo = (new Client())->getMessageRepository();
        $d = $this->createDiscussion();
        $last_id = null;
        for($i=0;$i<10;++$i) {
            $m = $this->createMessage($d);
            $last_id = $repo->save($m);
        }
        $msg = $repo->getLastOfDiscussion($d->getId());
        $this
            ->object($msg)->isInstanceOf('\Wizacha\Discuss\Entity\Message')
            ->integer($last_id)->isIdenticalTo($msg->getId())
        ;
    }

    public function test_getLastOfDiscussion_fail()
    {
        $client = new Client();
        $discussion_id = $client->getDiscussionRepository()->save($this->createDiscussion());
        $msg = $client->getMessageRepository()->getLastOfDiscussion($discussion_id);
        $this
            ->variable($msg)->isNull()
        ;
    }

    public function test_save_triggerAnEvent()
    {
        $client = new Client();
        $atoum  = $this;
        $msg    = $this->createMessage();
        $count  = 0;

        $client->getEventDispatcher()->addListener(
            DiscussEvents::MESSAGE_NEW,
            function ($event) use ($atoum, $msg, &$count) {
                ++$count;
                $atoum
                    ->integer($count)->isIdenticalTo(1)
                    ->object($event)->isInstanceOf('Wizacha\Discuss\Event\MessageEvent')
                    ->object($event->getMessage())->isIdenticalTo($msg);
            }
        );

        $client->getMessageRepository()->save($msg);
        $client->getMessageRepository()->save($msg);
    }
}

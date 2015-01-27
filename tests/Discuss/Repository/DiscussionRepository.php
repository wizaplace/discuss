<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Repository\tests\unit;

use Wizacha\Discuss\Entity\Discussion\Status;
use Wizacha\Discuss\Tests\Client;
use Wizacha\Discuss\Tests\RepositoryTest;

class DiscussionRepository extends RepositoryTest
{
    public function test_createSaveGet_succeed()
    {
        $repo = (new Client())->getDiscussionRepository();
        $discu = $repo->create();
        $this->object($discu)->isInstanceOf('\Wizacha\Discuss\Entity\DiscussionInterface');


        $discu_data = [
            'Initiator' => 51,
            'Recipient' => 1664,
            'Open'      => true,
        ];

        $this->fillEntity($discu, $discu_data);

        $discu_id = $repo->save($discu);
        $this->integer($discu_id)->isGreaterThan(0);

        $discu = $repo->get($discu_id);
        $this->object($discu)->isInstanceOf('\Wizacha\Discuss\Entity\DiscussionInterface');

        $this->testEntityData($discu, $discu_data);
    }

    public function test_get_failIfNotExist()
    {
        $repo = (new Client())->getDiscussionRepository();
        $this->variable($repo->get(3))->isNull();
    }

    public function test_getIfUser_succeed()
    {
        $repo = (new Client())->getDiscussionRepository();
        $d = $this->createDiscussion();
        $id = $repo->save($d);

        $this
            ->integer($repo->getIfUser($id, $d->getInitiator())->getId())->isIdenticalTo($id)
            ->integer($repo->getIfUser($id, $d->getRecipient())->getId())->isIdenticalTo($id)
            ->variable($repo->getIfUser($id, 9999999))->isNull()
        ;
    }

    public function test_getByUser_IsAnAlias()
    {
        $this->mockGenerator->orphanize('__construct');
        $repo        = new \mock\Wizacha\Discuss\Repository\DiscussionRepository();
        $user_id     = 1;
        $nb_per_page = 2;
        $page        = 3;
        $result      = 4;
        $atoum       = $this;

        $repo->getMockController()->getAll =
            function($_user_id, $status, $_nb_per_page, $_page)
                use($user_id, $nb_per_page, $page, $result, $atoum)
            {
                $atoum
                    ->integer($_user_id)->isIdenticalTo($user_id)
                    ->variable($status->getValue())->isIdenticalTo(Status::DISPLAYED)
                    ->integer($_nb_per_page)->isIdenticalTo($nb_per_page)
                    ->integer($_page)->isIdenticalTo($page)
                ;
                return $result;
            }
        ;

        $this->integer($repo->getByUser($user_id, $nb_per_page, $page))->isIdenticalTo($result);
    }

    /**
     * @param \Wizacha\Discuss\Repository\DiscussionRepository $repo
     * @param callable $filterCallback with following parameters $recipient_id, $initiator_id, $recipient_hidden, $initiator_hidden
     * @return array Expected ids according to filter
     */
    public function _generateDataFor_getAll(\Wizacha\Discuss\Repository\DiscussionRepository $repo, $filterCallback)
    {
        $expected_ids = [];
        foreach ([0, 1, 2] as $initiator_id) {
            $recipient_id = $initiator_id + 1;
            foreach ([false, true] as $initiator_hidden) {
                foreach ([false, true] as $recipient_hidden) {
                    $discu      = $repo->create();
                    $discu_data = [
                        'Initiator' => $initiator_id,
                        'Recipient' => $recipient_id,
                    ];
                    $this->fillEntity($discu, $discu_data);
                    if($initiator_hidden) {
                        $discu->hideDiscussion($initiator_id);
                    }
                    if($recipient_hidden) {
                        $discu->hideDiscussion($recipient_id);
                    }
                    $id = $repo->save($discu);

                    if($filterCallback($recipient_id, $initiator_id, $recipient_hidden, $initiator_hidden)) {
                        $expected_ids[] = $id;
                    }
                }
            }
        }
        return $expected_ids;
    }

    public function test_getAll_FilterByUserAndStatusSucceed()
    {
        $repo = (new Client())->getDiscussionRepository();

        $user_id      = 1;
        $expected_ids = $this->_generateDataFor_getAll(
            $repo,
            function($recipient_id, $initiator_id, $recipient_hidden, $initiator_hidden) use ($user_id) {
                return
                    ($user_id == $recipient_id && !$recipient_hidden)
                    || ($user_id == $initiator_id && !$initiator_hidden)
                ;
            }
        );

        $result = [];
        foreach($repo->getAll($user_id, new Status(Status::DISPLAYED)) as $d) {
            $result[] = $d->getId();
        }
        sort($result);
        $this->array($result)->isIdenticalTo($expected_ids);
    }

    public function test_getAll_FilterByUserOnlySucceed()
    {
        $repo = (new Client())->getDiscussionRepository();

        $user_id      = 1;
        $expected_ids = $this->_generateDataFor_getAll(
            $repo,
            function($recipient_id, $initiator_id, $recipient_hidden, $initiator_hidden) use ($user_id) {
                return in_array($user_id, [$recipient_id, $initiator_id]);
            }
        );

        $result = [];
        foreach($repo->getAll($user_id) as $d) {
            $result[] = $d->getId();
        }
        sort($result);
        $this->array($result)->isIdenticalTo($expected_ids);
    }

    public function test_getAll_FilterByStatusOnlySucceed()
    {
        $repo = (new Client())->getDiscussionRepository();

        $expected_ids = $this->_generateDataFor_getAll(
            $repo,
            function($recipient_id, $initiator_id, $recipient_hidden, $initiator_hidden) {
                return $recipient_hidden || $initiator_hidden;
            }
        );

        $result = [];
        foreach($repo->getAll(null, new Status(Status::HIDDEN)) as $d) {
            $result[] = $d->getId();
        }
        sort($result);
        $this->array($result)->isIdenticalTo($expected_ids);
    }

    public function test_getAll_PaginationSucceed()
    {
        $repo = (new Client())->getDiscussionRepository();

        for($i=0;$i<10;++$i) {
            $repo->save($this->createDiscussion());
        }

        $pager = $repo->getAll();
        $this->object($pager)->isInstanceOf('\Countable')->isInstanceOf('\Traversable');
        //Retrieve All
        $this->object($pager)
            ->hasSize(10)
            ->array(iterator_to_array($pager))->hasSize(10)
        ;

        //Retrieve complete page
        $pager = $repo->getAll(null, null, 7);
        $this->object($pager)
            ->hasSize(10)
            ->array(iterator_to_array($pager))->hasSize(7)
        ;

        //Retrieve incomplete page
        $pager = $repo->getAll(null, null, 7, 1);
        $this->object($pager)
            ->hasSize(10)
            ->array(iterator_to_array($pager))->hasSize(3)
        ;

        //Retrieve empty page
        $pager = $repo->getAll(null, null, 7, 2);
        $this->object($pager)
            ->hasSize(10)
            ->array(iterator_to_array($pager))->hasSize(0)
        ;
    }
}

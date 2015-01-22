<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Repository\tests\unit;

use Wizacha\Discuss\Tests\Client;
use Wizacha\Discuss\Tests\RepositoryTest;

class DiscussionRepository extends RepositoryTest
{
    public function test_set_get_succeed()
    {
        $repo = (new Client())->getDiscussionRepository();
        $discu = $repo->get();
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

    public function test_getByUser_FilterSucceed()
    {
        $repo = (new Client())->getDiscussionRepository();

        $user_id      = 1;
        $expected_ids = [];
        foreach ([0, 1, 2] as $initiator_id) {
            $recipient_id = $initiator_id + 1;
            foreach ([false, true] as $initiator_hidden) {
                foreach ([false, true] as $recipient_hidden) {
                    $discu      = $repo->get();
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

                    if(
                        ($user_id == $recipient_id && !$recipient_hidden)
                        || ($user_id == $initiator_id && !$initiator_hidden)
                    ) {
                        $expected_ids[] = $id;
                    }
                }
            }
        }

        foreach($repo->getByUser($user_id) as $d) {
            $result[] = $d->getId();
        }
        sort($result);
        $this->array($result)->isIdenticalTo($expected_ids);
    }

    public function test_getByUser_PaginationSucceed()
    {
        $repo = (new Client())->getDiscussionRepository();

        for($i=0;$i<10;++$i) {
            $d = $repo->get();
            $this->fillEntity($d, ['Initiator' => 1, 'Recipient' => 1]);
            $repo->save($d);
        }

        $pager = $repo->getByUser(1);
        $this->object($pager)->isInstanceOf('\Countable')->isInstanceOf('\Traversable');
        //Retrieve All
        $this->object($pager)
            ->hasSize(10)
            ->array(iterator_to_array($pager))->hasSize(10)
        ;

        //Retrieve complete page
        $pager = $repo->getByUser(1, 7);
        $this->object($pager)
            ->hasSize(10)
            ->array(iterator_to_array($pager))->hasSize(7)
        ;

        //Retrieve incomplete page
        $pager = $repo->getByUser(1, 7, 1);
        $this->object($pager)
            ->hasSize(10)
            ->array(iterator_to_array($pager))->hasSize(3)
        ;

        //Retrieve empty page
        $pager = $repo->getByUser(1, 7, 2);
        $this->object($pager)
            ->hasSize(10)
            ->array(iterator_to_array($pager))->hasSize(0)
        ;
    }
}

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
}

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
    public function test_set_get_succeed()
    {
        $repo = (new Client())->getMessageRepository();
        $msg = $repo->get();
        $this->object($msg)->isInstanceOf('\Wizacha\Discuss\Entity\MessageInterface');

        $msg_data = [
            'Author'    => 51,
            'Content'   => 'This is a Content',
            'SendDate'  => new \DateTime(),
        ];
        $this->fillEntity($msg, $msg_data);

        $msg_id = $repo->save($msg);
        $this->integer($msg_id)->isGreaterThan(0);

        $msg = $repo->get($msg_id);
        $this->object($msg)->isInstanceOf('\Wizacha\Discuss\Entity\MessageInterface');

        $this->testEntityData($msg, $msg_data);
    }
}

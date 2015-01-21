<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Repository\tests\unit;

use mageekguy\atoum;
use Wizacha\Discuss\Entity\MessageInterface;
use Wizacha\Discuss\Tests\Client;

class MessageRepository extends atoum\test
{
    protected function fillMessage(MessageInterface $msg, array $msg_data)
    {
        foreach($msg_data as $name => $data) {
            $method = "set$name";
            $msg->$method($data);
        }
    }

    protected function testMessageData(MessageInterface $msg, array $msg_data)
    {
        foreach($msg_data as $name => $data) {
            $method = "get$name";
            $this->variable($msg->$method())->isIdenticalTo($data);
        }
    }

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
        $this->fillMessage($msg, $msg_data);

        $msg_id = $repo->save($msg);
        $this->integer($msg_id)->isGreaterThan(0);

        $msg = $repo->get($msg_id);
        $this->object($msg)->isInstanceOf('\Wizacha\Discuss\Entity\MessageInterface');

        $this->testMessageData($msg, $msg_data);
    }
}

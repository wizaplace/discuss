<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\tests\unit;

use mageekguy\atoum;

class Client extends atoum\test
{

    public function test_ctor_FailedWithBadParams()
    {
        $this->exception(
            function() {
                new \Wizacha\Discuss\Client([], true);
            }
        )->isInstanceOf('\Doctrine\DBAL\DBALException');
    }

    public function test_getMessageRepository_succeed()
    {
        $client = new \Wizacha\Discuss\Tests\Client();
        $this
            ->object($client->getMessageRepository())
            ->isInstanceOf('\Wizacha\Discuss\Repository\MessageRepository')
        ;
    }

    public function test_getDiscussionRepository_succeed()
    {
        $client = new \Wizacha\Discuss\Tests\Client();
        $this
            ->object($client->getDiscussionRepository())
            ->isInstanceOf('\Wizacha\Discuss\Repository\DiscussionRepository')
        ;
    }

    public function test_getEventDispatcher_succeed()
    {
        $client = new \Wizacha\Discuss\Tests\Client();
        $this
            ->object($client->getEventDispatcher())
            ->isInstanceOf('\Symfony\Component\EventDispatcher\EventDispatcher')
        ;
    }
}



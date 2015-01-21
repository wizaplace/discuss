<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Entity\tests\unit;

use mageekguy\atoum;
use Wizacha\Discuss\Entity\Message as MessageTest;

class Message extends atoum\test
{
    public function test_getId_IsNullOnNewEntity()
    {
        $msg = new MessageTest();
        $this->variable($msg->getId())->isNull();
    }
}

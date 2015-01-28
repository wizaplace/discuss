<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Entity\Discussion\tests\unit;

use mageekguy\atoum;
use Wizacha\Discuss\Entity\Discussion\MetaData as MetaDataTest;

class MetaData extends atoum\test
{
    public function test_constructAndGet_succeed()
    {
        $d     = new \mock\Wizacha\Discuss\Entity\DiscussionInterface;
        $key   = 'key';
        $value = 'value';
        $m     = new MetaDataTest($d, $key, $value);

        $this
            ->string($m->getKey())->isIdenticalTo($key)
            ->string($m->getValue())->isIdenticalTo($value)
            ->string((string)$m)->isIdenticalTo($value)
        ;
    }
}

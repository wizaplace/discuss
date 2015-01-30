<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Internal\Entity\tests\unit;

use mageekguy\atoum;
use Wizacha\Discuss\Internal\Entity\MetaData as MetaDataTest;

class MetaData extends atoum\test
{
    public function test_constructAndGet_succeed()
    {
        $d     = new \mock\Wizacha\Discuss\Entity\DiscussionInterface;
        $name  = 'name';
        $value = 'value';
        $m     = new MetaDataTest($d, $name, $value);

        $this
            ->string($m->getName())->isIdenticalTo($name)
            ->string($m->getValue())->isIdenticalTo($value)
            ->string((string)$m)->isIdenticalTo($value)
        ;
    }
}

<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Internal\tests\unit;

use Doctrine\ORM\EntityManagerInterface;
use Wizacha\Discuss\Internal\EntityManagerAware as EntityManagerAwareTest;
use mageekguy\atoum;

class EntityManagerAware extends atoum\test
{
    public function test_getEntityManager_succeedWithConstructor()
    {
        $em = new \mock\Doctrine\ORM\EntityManagerInterface;
        $obj = new ChildClassWithConstructor($em);
        $this->object($obj->em)->isIdenticalTo($em);
    }

    public function test_getEntityManager_isNullWithoutConstructor()
    {
        $obj = new ChildClassWithoutConstructor();
        $this->variable($obj->em)->isNull();
    }
}


class ChildClassWithConstructor extends EntityManagerAwareTest
{
    public $em = null;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
        $this->em = $this->getEntityManager();
    }
}

class ChildClassWithoutConstructor extends EntityManagerAwareTest
{
    public $em = null;

    public function __construct()
    {
        $this->em = $this->getEntityManager();
    }
}

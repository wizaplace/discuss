<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Tests;

use mageekguy\atoum;

class RepositoryTest extends atoum\test
{
    protected function fillEntity($entity, array $entity_data)
    {
        foreach($entity_data as $name => $data) {
            $method = "set$name";
            $entity->$method($data);
        }
    }

    protected function testEntityData($entity, array $entity_data)
    {
        foreach($entity_data as $name => $data) {
            $method = "get$name";
            $this->variable($entity->$method())->isIdenticalTo($data);
        }
    }


} 
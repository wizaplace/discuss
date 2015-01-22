<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Tests;

use mageekguy\atoum;
use Wizacha\Discuss\Entity\Discussion;
use Wizacha\Discuss\Entity\Message;

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

    /**
     * Create a valid message that can be saved.
     * Use it if the content does not matter for the test
     * @param Discussion $discussion The discussion to set, if nul a new one is created
     * @return Message
     */
    protected function createMessage(Discussion $discussion = null)
    {
        $m = new Message();
        $this->fillEntity($m, [
            'Author'    => 51,
            'SendDate'  => new \DateTime(),
            'Content'   => 'This is a Content',
            'Discussion'=> $discussion ? : $this->createDiscussion()
        ]);
        return $m;
    }

    /**
     * Create a valid discussion that can be saved.
     * Use it if the content does not matter for the test
     * @return Discussion
     */
    protected function createDiscussion()
    {
        $d = new Discussion();
        $this->fillEntity($d, [
            'Initiator' => 51,
            'Recipient' => 1664,
        ]);
        return $d;
    }

} 
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

    public function testAuthor()
    {
        $id = 1;
        $msg = new MessageTest();
        $msg->setAuthor($id);

        $this
            ->integer($msg->getAuthor())
            ->isIdenticalTo($id);

    }

    public function testSendDate()
    {
        $date = \DateTime::createFromFormat('d-m-Y H:i:s', '21-12-2012 01:02:03');
        $msg = new MessageTest();
        $msg->setSendDate($date);

        $this
            ->datetime($msg->getSendDate())
            ->isIdenticalTo($date);

    }

    public function testReadDate()
    {
        $date = \DateTime::createFromFormat('d-m-Y H:i:s', '21-12-2012 01:02:03');
        $msg = new MessageTest();
        $msg->setReadDate($date);

        $this
            ->datetime($msg->getReadDate())
            ->isIdenticalTo($date);

    }

    public function testContent()
    {
        $content = "<p>Portez ce vieux whisky au juge blond qui fume sur son île intérieure,
        à côté de l'alcôve ovoïde, où les bûches se consument dans l'âtre,
        ce qui lui permet de penser à la cænogénèse de l'être dont il est question dans la cause ambiguë entendue à Moÿ,
        dans un capharnaüm qui, pense-t-il, diminue çà et là la qualité de son œuvre.</p>";
        $msg = new MessageTest();
        $msg->setContent($content);

        $this
            ->string($msg->getContent())
            ->isIdenticalTo($content);

    }

    public function testDiscussion()
    {
        $discussion = 1;
        $msg = new MessageTest();
        $msg->setDiscussion($discussion);

        $this
            ->integer($msg->getDiscussion())
            ->isIdenticalTo($discussion);

    }
}

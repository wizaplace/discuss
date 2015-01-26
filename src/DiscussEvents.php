<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss;

/**
 * Class DiscussEvents
 * List all events sent by Discuss
 * @package Wizacha\Discuss
 */
final class DiscussEvents
{
    /**
     * Triggered when a new message is recorded, with an instance of
     * \Wizacha\Discuss\Event\MessageEvent
     */
    const MESSAGE_NEW   = 'message.new';
}

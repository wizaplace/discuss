<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Entity;


interface DiscussionInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $initiator
     * @return boolean
     */
    public function setInitiator($initiator);

    /**
     * @return int
     */
    public function getInitiator();

    /**
     * @param int $recipient
     * @return boolean
     */
    public function setRecipient($recipient);

    /**
     * @return int
     */
    public function getRecipient();

    /**
     * @return \Wizacha\Discuss\Entity\Discussion
     */
    public function getStatusInitiator();

    /**
     * @return boolean
     */
    public function getOpen();

    /**
     * @param boolean $open
     */
    public function setOpen($open);

    /**
     * @param $user_id
     * @return mixed
     */
    public function hideDiscussion($user_id);

    /**
     * @return \Wizacha\Discuss\Entity\Discussion
     */
    public function getStatusRecipient();
}

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
     * @return $this
     */
    public function setInitiator($initiator);

    /**
     * @return int
     */
    public function getInitiator();

    /**
     * @param int $recipient
     * @return $this
     */
    public function setRecipient($recipient);

    /**
     * @return int
     */
    public function getRecipient();

    /**
     * @return \Wizacha\Discuss\Entity\Discussion\Status
     */
    public function getStatusInitiator();

    /**
     * @return boolean
     */
    public function getOpen();

    /**
     * @param boolean $open
     * @return $this
     */
    public function setOpen($open);

    /**
     * Hide the discussion for a user. Do nothing if the user is not implied in discussion
     * @param integer $user_id
     * @return $this
     */
    public function hideDiscussion($user_id);

    /**
     * @return \Wizacha\Discuss\Entity\Discussion\Status
     */
    public function getStatusRecipient();
}

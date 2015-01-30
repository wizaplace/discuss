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
     * @return int[]
     */
    public function getUsers();

    /**
     * @param int $user_id
     * @return int
     */
    public function getOtherUser($user_id);

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

    /**
     * Gets value associated to a name, or null if not exists
     * @param string $name
     * @return string|null
     */
    public function getMetaData($name);

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setMetaData($name, $value);
}

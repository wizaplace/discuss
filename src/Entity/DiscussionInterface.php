<?php
/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 20/01/15
 * Time: 16:09
 */

namespace Wizacha\Discuss\Entity;


interface DiscussionInterface {
    /**
     * @return int
     */
    public function getId();

    /**
     * @param mixed $initiator
     */
    public function setInitiator($initiator);

    /**
     * @return mixed
     */
    public function getInitiator();

    /**
     * @param int $recipient
     */
    public function setRecipient($recipient);

    /**
     * @return int
     */
    public function getRecipient();

    /**
     * @param \Wizacha\Discuss\Entity\Discussion $status_initiator
     */
    public function setStatusInitiator($status_initiator);

    /**
     * @return \Wizacha\Discuss\Entity\Discussion
     */
    public function getStatusInitiator();

    /**
     * @param \Wizacha\Discuss\Entity\Discussion $status_recipient
     */
    public function setStatusRecipient($status_recipient);

    /**
     * @return \Wizacha\Discuss\Entity\Discussion
     */
    public function getStatusRecipient();

} 
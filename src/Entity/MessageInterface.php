<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Entity;


interface MessageInterface
{

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $author
     * @return $this
     */
    public function setAuthor($author);

    /**
     * @return int
     */
    public function getAuthor();

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param \DateTime $send_date
     * @return $this
     */
    public function setSendDate($send_date);

    /**
     * @return \DateTime
     */
    public function getSendDate();

    /**
     * @param \DateTime $read_date
     * @return $this
     */
    public function setReadDate($read_date);

    /**
     * @return \DateTime
     */
    public function getReadDate();

    /**
     * @param \Wizacha\Discuss\Entity\DiscussionInterface $discussion
     * @return $this
     */
    public function setDiscussion(DiscussionInterface $discussion);

    /**
     * @return \Wizacha\Discuss\Entity\DiscussionInterface
     */
    public function getDiscussion();
}

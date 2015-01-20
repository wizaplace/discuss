<?php
/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 20/01/15
 * Time: 16:12
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
     */
    public function setAuthor($author);

    /**
     * @return int
     */
    public function getAuthor();

    /**
     * @param string $content
     */
    public function setContent($content);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param \DateTime $send_date
     */
    public function setSendDate($send_date);

    /**
     * @return \DateTime
     */
    public function getSendDate();

    /**
     * @param \DateTime $read_date
     */
    public function setReadDate($read_date);

    /**
     * @return \DateTime
     */
    public function getReadDate();
}

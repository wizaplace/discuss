<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Internal\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Wizacha\Discuss\Entity\DiscussionInterface;

/**
 * Class MetaData
 * @package Wizacha\Discuss\Internal\Entity
 * @Entity()
 * @Table(uniqueConstraints={@UniqueConstraint(name="unique_key_by_discussion", columns={"discussion_id", "key"})})
 */
class MetaData
{
    /**
     * @Id()
     * @Column(type="integer")
     * @GeneratedValue()
     * @var
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="\Wizacha\Discuss\Entity\Discussion", inversedBy="meta_data")
     * @var DiscussionInterface
     */
    protected $discussion;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $key;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $value;

    /**
     * @param DiscussionInterface $discussion
     * @param string $key
     * @param string $value
     */
    public function __construct(DiscussionInterface $discussion, $key, $value)
    {
        $this->discussion = $discussion;
        $this->key        = $key;
        $this->value      = $value;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }
}

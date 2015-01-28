<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Internal;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class EntityManagerAware
 * Allows some classes to share reference to an EntityManager without
 * exposing it publicly
 * @package Wizacha\Discuss\Internal
 */
class EntityManagerAware
{
    /**
     * @var EntityManagerInterface
     */
    private $entity_manager = null;

    /**
     * @param EntityManagerInterface $entityManager
     */
    protected function __construct(EntityManagerInterface $entityManager)
    {
        $this->entity_manager = $entityManager;
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager()
    {
        return $this->entity_manager;
    }
}

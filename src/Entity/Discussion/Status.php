<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Entity\Discussion;

use MyCLabs\Enum\Enum;

class Status extends Enum
{
    const DISPLAYED = 'D';
    const HIDDEN    = 'H';
}

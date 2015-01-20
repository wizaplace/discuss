<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\Entity\Discussion;

use MyCLabs\Enum\Enum;

class Status extends Enum {
    const OPEN = 'O';
    const CLOSED = 'C';
    const HIDDEN = 'H';
}

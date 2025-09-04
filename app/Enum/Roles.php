<?php

namespace App\Enum;

use App\Enum\Traits\EnumTrait;

enum Roles: string
{
    use EnumTrait;

    case CLIENT = 'client';
    case SELLER = 'seller';
    case MASTER = 'master';
}

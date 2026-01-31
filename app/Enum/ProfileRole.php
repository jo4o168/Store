<?php

namespace App\Enum;

enum ProfileRole: int
{
    case CLIENT = 0;
    case PRODUCER = 1;
    case ADMIN = 2;
}

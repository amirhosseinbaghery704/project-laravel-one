<?php

namespace App\Enums;

use ArchTech\Enums\Values;

enum CommentStatuEnum: string
{
    use Values;

    case ACCEPT = 'accept';
    case PENDING = 'pending';
    case BLOCK = 'block';
}

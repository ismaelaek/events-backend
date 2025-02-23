<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumToArray;

enum EveentParticipantStatus: string
{
    use EnumToArray;

    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
}

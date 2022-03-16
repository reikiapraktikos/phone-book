<?php

declare(strict_types=1);

namespace App\Enum;

enum Message: string
{
    case USER_CREATED = 'User created';
    case RECORD_CREATED = 'Record created';
    case RECORD_UPDATED = 'Record updated';
    case RECORD_DELETED = 'Record deleted';
    case RECORD_SHARED = 'Record shared';
    case RECORD_SHARING_CANCELED = 'Record sharing canceled';
}

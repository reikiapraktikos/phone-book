<?php

declare(strict_types=1);

namespace App\Enum;

enum ExceptionMessage: string
{
    case DEFAULT = 'Something went wrong';
    case USER_CANT_BE_CREATED = 'User can\'t be created';
    case RECORD_CANT_BE_CREATED = 'Record can\'t be created';
    case RECORD_CANT_BE_UPDATED = 'Record can\'t be updated';
    case RECORD_CANT_BE_DELETED = 'Record can\'t be deleted';
    case RECORD_CANT_BE_SHARED = 'Record can\'t be shared';
    case RECORD_SHARING_CANT_BE_CANCELED = 'Record sharing can\'t be canceled';
    case UNABLE_TO_GET_AUTHENTICATED_USER = 'Unable to get authenticated user';
    case RECORD_NOT_FOUND = 'Record not found or you don\'t have permissions';
    case USER_NOT_FOUND = 'User not found';
    case RECORD_USER_NOT_FOUND = 'Record user not found';
    case UNABLE_TO_GET_RECORDS = 'Unable to get records';
}

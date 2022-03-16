<?php

declare(strict_types=1);

namespace App\Handler\Record;

use App\Entity\Record;
use App\Entity\User;
use App\Enum\ExceptionMessage;
use App\Exception\CustomException;
use App\Repository\RecordRepository;
use Exception;

final class GetRecordsHandler
{
    public function __construct(private RecordRepository $recordRepository)
    {
    }

    /**
     * @param User $user
     * @return Record[]
     * @throws CustomException
     */
    public function handle(User $user): array
    {
        try {
            return $this->recordRepository->findAllByUser($user);
        } catch (Exception $e) {
            throw new CustomException(ExceptionMessage::UNABLE_TO_GET_RECORDS->value);
        }
    }
}

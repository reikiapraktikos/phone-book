<?php

declare(strict_types=1);

namespace App\Handler\Record;

use App\Entity\User;
use App\Enum\ExceptionMessage;
use App\Exception\CustomException;
use App\Repository\RecordRepository;
use Exception;

final class UpdateRecordHandler
{
    public function __construct(private RecordRepository $recordRepository)
    {
    }

    /**
     * @param User $user
     * @param array $recordArray
     * @return void
     * @throws CustomException
     */
    public function handle(User $user, array $recordArray): void
    {
        try {
            $record = $this->recordRepository->findOneByOwnerUserAndId($user, $recordArray['id']);

            if ($record === null) {
                throw new CustomException(ExceptionMessage::RECORD_NOT_FOUND->value);
            }

            if (!empty($recordArray['name'])) {
                $record->setName($recordArray['name']);
            }

            if (!empty($recordArray['number'])) {
                $record->setNumber($recordArray['number']);
            }

            $this->recordRepository->add($record);
        } catch (Exception $e) {
            if (!$e instanceof CustomException) {
                throw new CustomException(ExceptionMessage::RECORD_CANT_BE_UPDATED->value);
            }

            throw $e;
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Handler\Record;

use App\Entity\Record;
use App\Entity\RecordUser;
use App\Entity\User;
use App\Enum\ExceptionMessage;
use App\Exception\CustomException;
use App\Repository\RecordRepository;
use Exception;

final class CreateRecordHandler
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
        $recordUser = new RecordUser();
        $recordUser
            ->setUser($user)
            ->setIsOwner(true);
        $record = new Record();
        $record
            ->setName($recordArray['name'])
            ->setNumber($recordArray['number'])
            ->addRecordUser($recordUser);

        try {
            $this->recordRepository->add($record);
        } catch (Exception $e) {
            throw new CustomException(ExceptionMessage::RECORD_CANT_BE_CREATED->value);
        }
    }
}

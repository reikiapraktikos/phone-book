<?php

declare(strict_types=1);

namespace App\Handler\Record;

use App\Entity\RecordUser;
use App\Entity\User;
use App\Enum\ExceptionMessage;
use App\Exception\CustomException;
use App\Repository\RecordRepository;
use App\Repository\UserRepository;
use Exception;

final class ShareRecordHandler
{
    public function __construct(private RecordRepository $recordRepository, private UserRepository $userRepository)
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

            $anotherUser = $this->userRepository->findOneBy(['id' => $recordArray['user_id']]);

            if ($anotherUser === null) {
                throw new CustomException(ExceptionMessage::USER_NOT_FOUND->value);
            }

            $recordUser = new RecordUser();
            $recordUser
                ->setUser($anotherUser)
                ->setIsOwner(false);
            $record->addRecordUser($recordUser);

            $this->recordRepository->add($record);
        } catch (Exception $e) {
            if (!$e instanceof CustomException) {
                throw new CustomException(ExceptionMessage::RECORD_CANT_BE_SHARED->value);
            }

            throw $e;
        }
    }
}

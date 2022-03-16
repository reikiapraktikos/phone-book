<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Entity\User;
use App\Enum\ExceptionMessage;
use App\Exception\CustomException;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class CreateUserHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    /**
     * @param array $userArray
     * @return void
     * @throws CustomException
     */
    public function handle(array $userArray): void
    {
        $user = new User();
        $user
            ->setEmail($userArray['email'])
            ->setPassword($this->passwordHasher->hashPassword($user, $userArray['password']));

        try {
            $this->userRepository->add($user);
        } catch (Exception $e) {
            throw new CustomException(ExceptionMessage::USER_CANT_BE_CREATED->value);
        }
    }
}

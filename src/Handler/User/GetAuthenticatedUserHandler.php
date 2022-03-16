<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Enum\ExceptionMessage;
use App\Exception\CustomException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class GetAuthenticatedUserHandler
{
    public function __construct(private TokenStorageInterface $tokenStorage)
    {
    }

    /**
     * @return UserInterface
     * @throws CustomException
     */
    public function handle(): UserInterface
    {
        $user = null;
        $token = $this->tokenStorage->getToken();

        if ($token instanceof TokenInterface) {
            $user = $token->getUser();
        }

        if ($user === null) {
            throw new CustomException(ExceptionMessage::UNABLE_TO_GET_AUTHENTICATED_USER->value);
        }

        return $user;
    }
}

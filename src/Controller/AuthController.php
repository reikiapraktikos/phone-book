<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\Message;
use App\Handler\User\CreateUserHandler;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
final class AuthController
{
    public function __construct(private CreateUserHandler $createUserHandler)
    {
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $userArray = json_decode($request->getContent(), true);

        try {
            $this->createUserHandler->handle($userArray);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }

        return new JsonResponse(Message::USER_CREATED->value);
    }
}

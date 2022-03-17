<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Enum\Message;
use App\Handler\Record\CancelRecordSharingHandler;
use App\Handler\Record\CreateRecordHandler;
use App\Handler\Record\DeleteRecordHandler;
use App\Handler\Record\GetRecordsHandler;
use App\Handler\Record\ShareRecordHandler;
use App\Handler\Record\UpdateRecordHandler;
use App\Handler\User\GetAuthenticatedUserHandler;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/record')]
final class RecordController
{
    public function __construct(
        private GetAuthenticatedUserHandler $getAuthenticatedUserHandler,
        private CreateRecordHandler $createRecordHandler,
        private GetRecordsHandler $getRecordsHandler,
        private UpdateRecordHandler $updateRecordHandler,
        private DeleteRecordHandler $deleteRecordHandler,
        private ShareRecordHandler $shareRecordHandler,
        private CancelRecordSharingHandler $cancelRecordSharingHandler
    ) {
    }

    #[Route('', name: 'record_get', methods: ['GET'])]
    public function get(): JsonResponse
    {
        try {
            $result = [];
            /** @var User $authenticatedUser */
            $authenticatedUser = $this->getAuthenticatedUserHandler->handle();
            $records = $this->getRecordsHandler->handle($authenticatedUser);

            foreach ($records as $record) {
                $result[] = [
                    'id' => $record->getId(),
                    'name' => $record->getName(),
                    'number' => $record->getNumber()
                ];
            }
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }

        return new JsonResponse($result);
    }

    #[Route('', name: 'record_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            /** @var User $authenticatedUser */
            $authenticatedUser = $this->getAuthenticatedUserHandler->handle();
            $recordArray = json_decode($request->getContent(), true);
            $this->createRecordHandler->handle($authenticatedUser, $recordArray);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }

        return new JsonResponse(Message::RECORD_CREATED->value, Response::HTTP_CREATED);
    }

    #[Route('', name: 'record_update', methods: ['PUT'])]
    public function update(Request $request): JsonResponse
    {
        try {
            /** @var User $authenticatedUser */
            $authenticatedUser = $this->getAuthenticatedUserHandler->handle();
            $recordArray = json_decode($request->getContent(), true);
            $this->updateRecordHandler->handle($authenticatedUser, $recordArray);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }

        return new JsonResponse(Message::RECORD_UPDATED->value);
    }

    #[Route('/{id}', name: 'record_delete', methods: ['DELETE'])]
    public function delete(Request $request, int $id): JsonResponse
    {
        try {
            /** @var User $authenticatedUser */
            $authenticatedUser = $this->getAuthenticatedUserHandler->handle();
            $this->deleteRecordHandler->handle($authenticatedUser, $id);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }

        return new JsonResponse(Message::RECORD_DELETED->value);
    }

    #[Route('/share', name: 'record_share', methods: ['POST'])]
    public function share(Request $request): JsonResponse
    {
        try {
            /** @var User $authenticatedUser */
            $authenticatedUser = $this->getAuthenticatedUserHandler->handle();
            $recordArray = json_decode($request->getContent(), true);
            $this->shareRecordHandler->handle($authenticatedUser, $recordArray);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }

        return new JsonResponse(Message::RECORD_SHARED->value);
    }

    #[Route('/cancel-sharing', name: 'record_cancel_sharing', methods: ['POST'])]
    public function cancel(Request $request): JsonResponse
    {
        try {
            /** @var User $authenticatedUser */
            $authenticatedUser = $this->getAuthenticatedUserHandler->handle();
            $recordArray = json_decode($request->getContent(), true);
            $this->cancelRecordSharingHandler->handle($authenticatedUser, $recordArray);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }

        return new JsonResponse(Message::RECORD_SHARING_CANCELED->value);
    }
}

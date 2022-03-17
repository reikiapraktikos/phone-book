<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Entity\Record;
use App\Entity\RecordUser;
use App\Enum\Message;
use App\Repository\RecordRepository;
use App\Repository\RecordUserRepository;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class RecordControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private RecordRepository $recordRepository;
    private RecordUserRepository $recordUserRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        /** @var RecordRepository $recordRepository */
        $recordRepository = self::getContainer()->get(RecordRepository::class);
        $this->recordRepository = $recordRepository;
        /** @var RecordUserRepository $recordUserRepository */
        $recordUserRepository = self::getContainer()->get(RecordUserRepository::class);
        $this->recordUserRepository = $recordUserRepository;
        /** @var UserRepository $userRepository */
        $userRepository = self::getContainer()->get(UserRepository::class);
        $this->userRepository = $userRepository;
        /** @var AbstractDatabaseTool $databaseTool */
        $databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $databaseTool->setExcludedDoctrineTables(['user']);
        $databaseTool->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $databaseTool->loadFixtures();
    }

    public function testGet(): void
    {
        $providedRecords = [
            [
                'name' => 'foo',
                'number' => '111',
                'user_id' => 1,
            ],
            [
                'name' => 'bar',
                'number' => '222',
                'user_id' => 1,
            ],
        ];
        $result = [
            [
                'id' => 1,
                'name' => 'foo',
                'number' => '111',
            ],
            [
                'id' => 2,
                'name' => 'bar',
                'number' => '222',
            ],
        ];

        $this->addRecords($providedRecords);
        $this->authenticateClient();
        $this->client->request(
            'GET',
            '/api/record',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        self::assertSame(json_decode($this->client->getResponse()->getContent(), true), $result);
    }

    public function testCreate(): void
    {
        $name = 'foo';
        $number = '111';
        $this->authenticateClient();
        $this->client->request(
            'POST',
            '/api/record',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => $name,
                'number' => $number,
            ])
        );

        self::assertSame($this->client->getResponse()->getStatusCode(), Response::HTTP_CREATED);
        self::assertSame($this->client->getResponse()->getContent(), '"' . Message::RECORD_CREATED->value . '"');

        $record = $this->recordRepository->findOneBy(['id' => 1]);

        self::assertSame($record->getName(), $name);
        self::assertSame($record->getNumber(), $number);

        $recordUser = $this->recordUserRepository->findOneBy(['id' => 1]);

        self::assertSame($recordUser->getIsOwner(), true);
        self::assertSame($recordUser->getRecord(), $record);
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->addRecords();
        $this->authenticateClient();
        $this->client->request(
            'DELETE',
            sprintf('/api/record/%s', $id),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        self::assertSame($this->client->getResponse()->getStatusCode(), Response::HTTP_OK);
        self::assertSame($this->client->getResponse()->getContent(), '"' . Message::RECORD_DELETED->value . '"');
        self::assertSame($this->recordRepository->findOneBy(['id' => $id]), null);
    }

    public function testUpdate(): void
    {
        $id = 1;
        $name = 'bar';
        $number = '222';
        $this->addRecords();
        $this->authenticateClient();
        $this->client->request(
            'PUT',
            '/api/record',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => $id,
                'name' => $name,
                'number' => $number,
            ])
        );

        self::assertSame($this->client->getResponse()->getStatusCode(), Response::HTTP_OK);
        self::assertSame($this->client->getResponse()->getContent(), '"' . Message::RECORD_UPDATED->value . '"');

        $record = $this->recordRepository->findOneBy(['id' => $id]);

        self::assertSame($record->getName(), $name);
        self::assertSame($record->getNumber(), $number);
    }

    public function testShare(): void
    {
        $recordId = 1;
        $userId = 2;
        $this->addRecords();
        $this->authenticateClient();
        $this->client->request(
            'POST',
            '/api/record/share',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => $recordId,
                'user_id' => $userId,
            ])
        );

        self::assertSame($this->client->getResponse()->getStatusCode(), Response::HTTP_OK);
        self::assertSame($this->client->getResponse()->getContent(), '"' . Message::RECORD_SHARED->value . '"');

        $record = $this->recordRepository->findOneBy(['id' => $recordId]);
        $user = $this->userRepository->findOneBy(['id' => $userId]);
        $recordUser = $this->recordUserRepository->findOneBy(['record' => $record, 'user' => $user]);

        self::assertSame($recordUser->getIsOwner(), false);
    }

    public function testCancel(): void
    {
        $recordId = 1;
        $userId = 2;
        $this->addRecords();
        $this->authenticateClient();
        $this->client->request(
            'POST',
            '/api/record/share',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => $recordId,
                'user_id' => $userId,
            ])
        );
        $this->client->request(
            'POST',
            '/api/record/cancel-sharing',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => $recordId,
                'user_id' => $userId,
            ])
        );

        self::assertSame($this->client->getResponse()->getStatusCode(), Response::HTTP_OK);
        self::assertSame(
            $this->client->getResponse()->getContent(),
            '"' . Message::RECORD_SHARING_CANCELED->value . '"'
        );

        $record = $this->recordRepository->findOneBy(['id' => $recordId]);
        $user = $this->userRepository->findOneBy(['id' => $userId]);
        $recordUser = $this->recordUserRepository->findOneBy(['record' => $record, 'user' => $user]);

        self::assertSame($recordUser, null);
    }

    private function authenticateClient(string $email = 'test@test.com', string $password = 'test123'): void
    {
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => $email,
                'password' => $password,
            ])
        );
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
    }

    private function addRecords(
        array $providedRecords = [
            [
                'name' => 'foo',
                'number' => '111',
                'user_id' => 1,
            ]
        ]
    ): void {
        foreach ($providedRecords as $providedRecord) {
            $user = $this->userRepository->findOneBy(['id' => $providedRecord['user_id']]);
            $recordUser = new RecordUser();
            $recordUser
                ->setUser($user)
                ->setIsOwner(true);
            $record = new Record();
            $record
                ->setName($providedRecord['name'])
                ->setNumber($providedRecord['number'])
                ->addRecordUser($recordUser);
            $this->recordRepository->add($record);
        }
    }
}

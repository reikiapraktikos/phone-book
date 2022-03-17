<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixture extends Fixture
{
    private array $credentials = [
        [
            'email' => 'test@test.com',
            'password' => 'test123'
        ],
        [
            'email' => 'test1@test.com',
            'password' => 'test456'
        ]
    ];

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->credentials as $credential) {
            $user = new User();
            $user
                ->setEmail($credential['email'])
                ->setPassword($this->passwordHasher->hashPassword($user, $credential['password']));
            $manager->persist($user);
        }

        $manager->flush();
    }
}

<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RecordUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecordUserRepository::class)]
final class RecordUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Record::class, inversedBy: 'recordUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private $record;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'recordUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'boolean')]
    private $isOwner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecord(): ?Record
    {
        return $this->record;
    }

    public function setRecord(Record $record): self
    {
        $this->record = $record;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getIsOwner(): ?bool
    {
        return $this->isOwner;
    }

    public function setIsOwner(bool $isOwner): self
    {
        $this->isOwner = $isOwner;

        return $this;
    }
}

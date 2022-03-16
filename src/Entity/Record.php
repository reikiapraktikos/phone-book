<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RecordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecordRepository::class)]
final class Record
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $number;

    #[ORM\OneToMany(mappedBy: 'record', targetEntity: RecordUser::class, cascade: ['persist'])]
    private $recordUsers;

    public function __construct()
    {
        $this->recordUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection<int, RecordUser>
     */
    public function getRecordUsers(): Collection
    {
        return $this->recordUsers;
    }

    public function addRecordUser(RecordUser $recordUser): self
    {
        if (!$this->recordUsers->contains($recordUser)) {
            $this->recordUsers[] = $recordUser;
            $recordUser->setRecord($this);
        }

        return $this;
    }
}

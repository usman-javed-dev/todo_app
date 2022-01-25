<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Task Entity
 * 
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tasks")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $attachmentFileName;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAttachmentFileName(): ?string
    {
        return $this->attachmentFileName;
    }

    public function setAttachmentFileName(?string $attachmentFileName): self
    {
        $this->attachmentFileName = $attachmentFileName;

        return $this;
    }
}

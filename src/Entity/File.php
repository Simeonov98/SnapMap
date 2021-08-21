<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FileRepository::class)
 */
class File
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Coord_file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Archive_file;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Created_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="File_id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoordFile(): ?string
    {
        return $this->Coord_file;
    }

    public function setCoordFile(string $Coord_file): self
    {
        $this->Coord_file = $Coord_file;

        return $this;
    }

    public function getArchiveFile(): ?string
    {
        return $this->Archive_file;
    }

    public function setArchiveFile(?string $Archive_file): self
    {
        $this->Archive_file = $Archive_file;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->Created_at;
    }

    public function setCreatedAt(\DateTimeInterface $Created_at): self
    {
        $this->Created_at = $Created_at;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->User_id;
    }

    public function setUserId(?User $User_id): self
    {
        $this->User_id = $User_id;

        return $this;
    }
}

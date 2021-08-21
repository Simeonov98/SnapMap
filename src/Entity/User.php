<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
    private $Username;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Created_at;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="User_id")
     */
    private $File_id;

    public function __construct()
    {
        $this->File_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(string $Username): self
    {
        $this->Username = $Username;

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

    /**
     * @return Collection|File[]
     */
    public function getFileId(): Collection
    {
        return $this->File_id;
    }

    public function addFileId(File $fileId): self
    {
        if (!$this->File_id->contains($fileId)) {
            $this->File_id[] = $fileId;
            $fileId->setUserId($this);
        }

        return $this;
    }

    public function removeFileId(File $fileId): self
    {
        if ($this->File_id->removeElement($fileId)) {
            // set the owning side to null (unless already changed)
            if ($fileId->getUserId() === $this) {
                $fileId->setUserId(null);
            }
        }

        return $this;
    }
}

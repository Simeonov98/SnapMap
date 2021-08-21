<?php

namespace App\Entity;

use App\Repository\CoordRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CoordRepository::class)
 */
class Coord
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $Latitude;

    /**
     * @ORM\Column(type="float")
     */
    private $Longitude;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Zoom;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $UpperLeftLatitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $UpperLeftLongitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $LowerRightLatitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $LowerRightLongitude;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        return $this->Latitude;
    }

    public function setLatitude(float $Latitude): self
    {
        $this->Latitude = $Latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->Longitude;
    }

    public function setLongitude(float $Longitude): self
    {
        $this->Longitude = $Longitude;

        return $this;
    }

    public function getZoom(): ?int
    {
        return $this->Zoom;
    }

    public function setZoom(?int $Zoom): self
    {
        $this->Zoom = $Zoom;

        return $this;
    }

    public function getUpperLeftLatitude(): ?float
    {
        return $this->UpperLeftLatitude;
    }

    public function setUpperLeftLatitude(?float $UpperLeftLatitude): self
    {
        $this->UpperLeftLatitude = $UpperLeftLatitude;

        return $this;
    }

    public function getUpperLeftLongitude(): ?float
    {
        return $this->UpperLeftLongitude;
    }

    public function setUpperLeftLongitude(?float $UpperLeftLongitude): self
    {
        $this->UpperLeftLongitude = $UpperLeftLongitude;

        return $this;
    }

    public function getLowerRightLatitude(): ?float
    {
        return $this->LowerRightLatitude;
    }

    public function setLowerRightLatitude(?float $LowerRightLatitude): self
    {
        $this->LowerRightLatitude = $LowerRightLatitude;

        return $this;
    }

    public function getLowerRightLongitude(): ?float
    {
        return $this->LowerRightLongitude;
    }

    public function setLowerRightLongitude(?float $LowerRightLongitude): self
    {
        $this->LowerRightLongitude = $LowerRightLongitude;

        return $this;
    }
}

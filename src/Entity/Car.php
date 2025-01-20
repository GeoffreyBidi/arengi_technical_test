<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    public function __construct()
    {
        $this->category             = CarCategoryEnum::Other;
        $this->maximumAllowedWeight = 0;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $brand = null;

    #[ORM\Column(type: 'string', enumType: CarCategoryEnum::class)]
    private CarCategoryEnum $category;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $color = null;

    #[ORM\Column(type: 'integer')]
    private ?int $seatNumber = null;

    #[ORM\Column(type: 'integer')]
    private ?int $maximumAllowedWeight;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    private ?User $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getSeatNumber(): ?int
    {
        return $this->seatNumber;
    }

    public function setSeatNumber(int $seatNumber): self
    {
        $this->seatNumber = $seatNumber;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getMaximumAllowedWeight(): ?int
    {
        return $this->maximumAllowedWeight;
    }

    public function setMaximumAllowedWeight(int $maximumAllowedWeight): self
    {
        $this->maximumAllowedWeight = $maximumAllowedWeight;

        return $this;
    }

    public function getCategory(): CarCategoryEnum
    {
        return $this->category;
    }

    public function setCategory(CarCategoryEnum $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }
}

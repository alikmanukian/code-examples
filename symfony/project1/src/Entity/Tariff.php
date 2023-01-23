<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TariffRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TariffRepository::class)]
class Tariff
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'tariffs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $usageFrom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $validFrom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $validUntil = null;

    #[ORM\Column]
    private ?float $workingPriceNet = null;

    #[ORM\Column]
    private ?float $basePriceNet = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
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

    public function getUsageFrom(): ?int
    {
        return $this->usageFrom;
    }

    public function setUsageFrom(int $usageFrom): self
    {
        $this->usageFrom = $usageFrom;

        return $this;
    }

    public function getValidFrom(): ?DateTimeInterface
    {
        return $this->validFrom;
    }

    public function setValidFrom(DateTimeInterface $validFrom): self
    {
        $this->validFrom = $validFrom;

        return $this;
    }

    public function getValidUntil(): ?DateTimeInterface
    {
        return $this->validUntil->setTime(23, 59, 59);
    }

    public function setValidUntil(DateTimeInterface $validUntil): self
    {
        $this->validUntil = $validUntil;

        return $this;
    }

    public function getWorkingPriceNet(): ?float
    {
        return $this->workingPriceNet;
    }

    public function setWorkingPriceNet(float $workingPriceNet): self
    {
        $this->workingPriceNet = $workingPriceNet;

        return $this;
    }


    public function getBasePriceNet(): ?float
    {
        return $this->basePriceNet;
    }

    public function setBasePriceNet(float $basePriceNet): self
    {
        $this->basePriceNet = $basePriceNet;

        return $this;
    }
}

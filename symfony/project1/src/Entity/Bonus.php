<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BonusRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BonusRepository::class)]
class Bonus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $usageFrom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $validFrom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $validUntil = null;

    #[ORM\Column]
    private ?float $value = null;

    #[ORM\Column]
    private ?int $paymentAfterMonths = null;

    public function getId(): int
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

    public function getUsageFrom(): ?int
    {
        return $this->usageFrom;
    }

    public function setUsageFrom(int $usageFrom): self
    {
        $this->usageFrom = $usageFrom;

        return $this;
    }

    public function getValidFrom(): ?\DateTimeInterface
    {
        return $this->validFrom;
    }

    public function setValidFrom(DateTimeInterface $validFrom): self
    {
        $this->validFrom = $validFrom;

        return $this;
    }

    public function getValidUntil(): ?\DateTimeInterface
    {
        return $this->validUntil->setTime(23, 59, 59);
    }

    public function setValidUntil(DateTimeInterface $validUntil): self
    {
        $this->validUntil = $validUntil;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getPaymentAfterMonths(): ?int
    {
        return $this->paymentAfterMonths;
    }

    public function setPaymentAfterMonths(int $paymentAfterMonths): self
    {
        $this->paymentAfterMonths = $paymentAfterMonths;

        return $this;
    }
}

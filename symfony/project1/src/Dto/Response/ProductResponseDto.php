<?php

declare(strict_types=1);

namespace App\Dto\Response;

use JMS\Serializer\Annotation as Serialization;
use ReflectionClass;

class ProductResponseDto
{
    #[Serialization\Type("string")]
    private string $productName;

    #[Serialization\Type("float")]
    private float $workingPriceNet;

    #[Serialization\Type("float")]
    private float $basePriceNet;

    #[Serialization\Type("array")]
    private array $downPayment;

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return null;
    }

    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
    }

    public function toArray(): array
    {
        $reflection = new ReflectionClass($this);

        $data = [];

        foreach ($reflection->getProperties() as $item) {
            $data[$item->name] = $this->{$item->name};
        }

        return $data;
    }

}
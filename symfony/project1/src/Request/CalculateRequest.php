<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CalculateRequest
{
    #[Assert\NotBlank(null, 'vat is required')]
    #[Assert\Type('numeric', 'vat should be numeric')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'vat should be greater or equal to 0')]
    #[Assert\LessThan(value: 100, message: 'vat should be less or equal to 100')]
    public $vat;

    #[Assert\NotBlank(null, 'down_payment_interval is required')]
    #[Assert\Type('numeric', 'down_payment_interval should be numeric')]
    #[Assert\GreaterThanOrEqual(value: 1, message: 'down_payment_interval should be greater or equal to 1')]
    public $down_payment_interval;

    #[Assert\NotBlank(null, 'yearly_usage is required')]
    #[Assert\Type('numeric', 'yearly_usage should be numeric')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'yearly_usage should be greater or equal to 0')]
    public $yearly_usage;

    public function __construct($vat, $down_payment_interval, $yearly_usage)
    {
        $this->vat = $vat;
        $this->down_payment_interval = $down_payment_interval;
        $this->yearly_usage = $yearly_usage;
    }
}
<?php

namespace Tests\Unit\Request;

use App\Request\CalculateRequest;
use ArgumentCountError;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CalculateRequestTest extends WebTestCase
{
    protected ValidatorInterface $validator;

    public function setUp(): void
    {
        $this->validator    = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        parent::setUp();
    }
    public function testVatParameterIsRequired(): void
    {
        $this->expectException(ArgumentCountError::class);
        $this->expectExceptionMessage('($vat) not passed');

        new CalculateRequest(
            down_payment_interval: 12,
            yearly_usage: 3500,
        );
    }

    public function testDownPaymentIntervalParameterIsRequired(): void
    {
        $this->expectException(ArgumentCountError::class);
        $this->expectExceptionMessage('($down_payment_interval) not passed');

        new CalculateRequest(
            vat: 19,
            yearly_usage: 3500,
        );
    }

    public function testYearlyUsageParameterIsRequired(): void
    {
        $this->expectException(ArgumentCountError::class);
        $this->expectExceptionMessage('exactly 3 expected');

        new CalculateRequest(
            vat: 19,
            down_payment_interval: 12,
        );
    }

    public function testVatIsRequired(): void
    {
        $errors = $this->validator->validate(new CalculateRequest(
            vat: null,
            down_payment_interval: 12,
            yearly_usage: 3500,
        ));

        self::assertEquals('vat is required', $errors[0]->getMessage());
    }

    public function testVatIsNumber(): void
    {
        $errors = $this->validator->validate(new CalculateRequest(
            vat: 'test',
            down_payment_interval: 12,
            yearly_usage: 3500,
        ));

        self::assertEquals('vat should be numeric', $errors[0]->getMessage());
    }

    public function testVatGreaterOrEqual0(): void
    {
        $errors = $this->validator->validate(new CalculateRequest(
            vat: -10,
            down_payment_interval: 12,
            yearly_usage: 3500,
        ));

        self::assertEquals('vat should be greater or equal to 0', $errors[0]->getMessage());
    }

    public function testVatLessOrEqual100(): void
    {
        $errors = $this->validator->validate(new CalculateRequest(
            vat: 120,
            down_payment_interval: 12,
            yearly_usage: 3500,
        ));

        self::assertEquals('vat should be less or equal to 100', $errors[0]->getMessage());
    }

    public function testYearlyUsageIsRequired(): void
    {
        $errors = $this->validator->validate(new CalculateRequest(
            vat: 19,
            down_payment_interval: 12,
            yearly_usage: null,
        ));

        self::assertEquals('yearly_usage is required', $errors[0]->getMessage());
    }

    public function testYearlyUsageIsNumber(): void
    {
        $errors = $this->validator->validate(new CalculateRequest(
            vat: 19,
            down_payment_interval: 12,
            yearly_usage: 'test',
        ));

        self::assertEquals('yearly_usage should be numeric', $errors[0]->getMessage());
    }

    public function testYearlyUsageGreaterOrEqual0(): void
    {
        $errors = $this->validator->validate(new CalculateRequest(
            vat: 19,
            down_payment_interval: 12,
            yearly_usage: -10,
        ));

        self::assertEquals('yearly_usage should be greater or equal to 0', $errors[0]->getMessage());
    }

    public function testDownPaymentIntervalIsRequired(): void
    {
        $errors = $this->validator->validate(new CalculateRequest(
            vat: 19,
            down_payment_interval: null,
            yearly_usage: 3500,
        ));

        self::assertEquals('down_payment_interval is required', $errors[0]->getMessage());
    }

    public function testDownPaymentIntervalIsNumber(): void
    {
        $errors = $this->validator->validate(new CalculateRequest(
            vat: 19,
            down_payment_interval: 'test',
            yearly_usage: 3500,
        ));

        self::assertEquals('down_payment_interval should be numeric', $errors[0]->getMessage());
    }

    public function testDownPaymentIntervalGreaterOrEqual1(): void
    {
        $errors = $this->validator->validate(new CalculateRequest(
            vat: 19,
            down_payment_interval: 0,
            yearly_usage: 3500,
        ));

        self::assertEquals('down_payment_interval should be greater or equal to 1', $errors[0]->getMessage());
    }

    public function testValidateSuccess(): void
    {
        $errors = $this->validator->validate(new CalculateRequest(
            vat: 19,
            down_payment_interval: 12,
            yearly_usage: 3500,
        ));

        self::assertCount(0, $errors);
    }
}

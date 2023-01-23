<?php

declare(strict_types=1);

namespace Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerOutputFormatTest extends WebTestCase
{
    public function testResponseIsHtml(): void
    {
        $client = static::createClient();
        $client->request('GET', '/calculate', [
            'vat' => 19.00,
            'yearly_usage' => 3500,
            'down_payment_interval' => 12
        ]);

        self::assertResponseFormatSame('html');
    }

    public function testResponseIsJson(): void
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/calculate',
            parameters: [
                'vat' => 19.00,
                'yearly_usage' => 3500,
                'down_payment_interval' => 12
            ],
            server: [
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        self::assertResponseFormatSame('json');
    }
}

<?php

declare(strict_types=1);

namespace Tests\Functional\Controller;

use App\Entity\Product;
use App\Entity\Tariff;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    public function testGeneratesProductNotFoundException(): void
    {
        $client = static::createClient();
        $entityManager = self::getContainer()->get('doctrine')->getManager();

        /** @var Product $product */
        $product = $entityManager
            ->getRepository(Product::class)
            ->getProduct();

        $keepOldDate = $product->getValidUntil();

        // temporary changing product until date to get exception
        $product->setValidUntil(new DateTime('yesterday'));
        $entityManager->flush();

        $client->request('GET', '/calculate', [
            'vat' => 19.00,
            'yearly_usage' => 3500,
            'down_payment_interval' => 12
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        self::assertEquals("Product not found", $client->getResponse()->getContent());

        // return back old date
        $product->setValidUntil($keepOldDate);
        $entityManager->flush();
    }

    public function testGeneratesTariffNotFoundException(): void
    {
        $client = static::createClient();

        $entityManager = self::getContainer()->get('doctrine')->getManager();

        /** @var Product $product */
        $product = $entityManager
            ->getRepository(Product::class)
            ->getProduct();

        $tariffs = $product->getTariffs();

        // temporary changing tarrifs until date to get exception
        $tempDates = $tariffs->map(fn(Tariff $tariff) => $tariff->getValidUntil());
        $tariffs->map(function (Tariff $tariff) {
            $tariff->setValidUntil(new DateTime('yesterday'));
        });
        $entityManager->flush();

        $client->request('GET', '/calculate', [
            'vat' => 19.00,
            'yearly_usage' => 10000,
            'down_payment_interval' => 12
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        self::assertEquals("No tariff selected", $client->getResponse()->getContent());

        // return back old dates
        foreach ($tariffs as $index => $tariff) {
            $tariff->setValidUntil($tempDates[$index]);
        }
        $entityManager->flush();
    }

    public function testJsonContentFields(): void
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/calculate',
            parameters: [
                'vat' => 19.00,
                'yearly_usage' => 10000,
                'down_payment_interval' => 12
            ],
            server: [
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $arr = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey("productName", $arr);
        self::assertArrayHasKey("workingPriceNet", $arr);
        self::assertArrayHasKey("basePriceNet", $arr);
        self::assertArrayHasKey("downPayment", $arr);
        self::assertCount(12, $arr['downPayment']);
    }

    public function testHtmlContentFields(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/calculate', [
            'vat' => 19.00,
            'yearly_usage' => 10000,
            'down_payment_interval' => 12
        ]);

        self::assertResponseIsSuccessful();
        self::assertCount(2, $crawler->filter('body > div'));
        $firstDivCrawler = $crawler->filter('body > div')->first();
        self::assertCount(3, $firstDivCrawler->filter('p'));
        $lastDivCrawler = $crawler->filter('body > div')->last();
        self::assertCount(12, $lastDivCrawler->filter('p'));

        $children = $firstDivCrawler->children()->getIterator();
        self::assertStringContainsString('Product Name:', $children[0]->textContent);
        self::assertStringContainsString('Tariff Base Price Net:', $children[1]->textContent);
        self::assertStringContainsString('Tariff Working Price Net:', $children[2]->textContent);

        $lastDivCrawler->children()->each(function ($item, $index) {
            self::assertStringContainsString("Monthly down payment: " . ($index + 1), $item->text());
        });
    }
}

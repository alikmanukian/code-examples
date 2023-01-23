<?php

declare(strict_types=1);

namespace App\Command;

use JsonException;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'run:task',
    description: 'Run the task',
)]
class RunTaskCommand extends Command
{
    public function __construct(protected HttpClientInterface $client)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDefinition(
            new InputDefinition(
                [
                    new InputOption('output', 'o', InputOption::VALUE_REQUIRED),
                ]
            )
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $outputFormat = $input->getOption('output');

        if (!in_array($outputFormat, ['html', 'json'], true)) {
            throw new RuntimeException('Output format is not supported!');
        }

        $response = $this->client->request(
            'GET',
            "http://down-payment-calculator-webserver:{$_ENV['APP_PORT']}/calculate",
            [
                'query' => [
                    'vat' => '19.00',
                    'yearly_usage' => 3500,
                    'down_payment_interval' => 12
                ],
                'headers' => [
                    'Accept' => $outputFormat === 'json' ? 'application/json' : 'text/html'
                ]
            ]
        );

        echo $response->getContent() . "\n";

        return Command::SUCCESS;
    }
}

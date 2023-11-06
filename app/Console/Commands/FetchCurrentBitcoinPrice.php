<?php

namespace App\Console\Commands;

use App\DTO\BitcoinDto;
use App\Repository\PriceHistoryRepository;
use App\Repository\PriceHistoryRepositoryInterface;
use App\Service\Api\BitcoinClientInterface;
use App\Service\Api\Parser\BitcoinParserInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

class FetchCurrentBitcoinPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-current-bitcoin-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches bitcoin price from the configured API using HTTP';

    /**
     * Execute the console command.
     */
    public function handle(
        LoggerInterface $logger,
        BitcoinClientInterface $client,
        BitcoinParserInterface $parser,
        PriceHistoryRepositoryInterface $repository
    ) {
        $result = $client->getTickers();

        if (!$result->successful()) {
            $logger->error('API call was not successful');
            return;
        }

        $dtos = $parser->parse($result->json());
        $this->save($dtos, $repository);

        $logger->info('Info from cron at time: ' . (new Carbon())->format('Y-m-d H:i:s'));
    }

    private function save(array $dtos, PriceHistoryRepositoryInterface $repository): void
    {
        /** @var BitcoinDto $dto */
        foreach ($dtos as $dto) {
            $repository->store([
                'price'    => $dto->price,
                'currency' => $dto->currency
            ]);
        }
    }
}

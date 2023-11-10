<?php

namespace App\Console\Commands;

use App\DTO\BitcoinDto;
use App\Events\NewBitcoinPricesFetched;
use App\Models\PriceHistory;
use App\Repository\PriceHistoryRepositoryInterface;
use App\Service\Api\BitcoinClientInterface;
use App\Service\Utilities\CacheKeyCreator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        BitcoinClientInterface $client,
        PriceHistoryRepositoryInterface $repository,
    ) {
        $result = $client->getCurrentPrice();

        $this->saveFetchedResult($result, $repository);

        event(new NewBitcoinPricesFetched($result));
    }

    private function saveFetchedResult(array $dtos, PriceHistoryRepositoryInterface $repository): void
    {
        /** @var BitcoinDto $dto */
        foreach ($dtos as $dto) {
            $priceHistory = $repository->store([
                'price'    => $dto->price,
                'currency' => $dto->currency
            ]);

            if (!$priceHistory) {
                Log::error('Bitcoin price could not be saved');
                continue;
            }

            $this->saveInCache($priceHistory);
        }
    }

    private function saveInCache(PriceHistory $priceHistory): void
    {
        $cacheKey = CacheKeyCreator::createLatestPriceByCurrencyKey($priceHistory->currency);
        Cache::set($cacheKey, $priceHistory, 15);
    }
}

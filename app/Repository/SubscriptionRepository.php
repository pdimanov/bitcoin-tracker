<?php

namespace App\Repository;

use App\DTO\BitcoinDto;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function getValidSubscriptions(array $bitcoinDtos): Collection
    {
        $subscriptionsQuery = Subscription::query()
            ->where('is_notified', '=', false);

        /** @var BitcoinDto $bitcoinDto */
        $subscriptionsQuery->where(function ($query) use ($bitcoinDtos) {
            foreach ($bitcoinDtos as $bitcoinDto) {
                $query->orWhere(function ($query) use ($bitcoinDto) {
                    $query->where('currency', $bitcoinDto->currency)
                        ->where(function ($query) use ($bitcoinDto) {
                            $query->where(function ($query) use ($bitcoinDto) {
                                $query->where('is_increasing', 1)
                                    ->where('price', '<=', $bitcoinDto->price);
                            })->orWhere(function ($query) use ($bitcoinDto) {
                                $query->where('is_increasing', 0)
                                    ->where('price', '>=', $bitcoinDto->price);
                            });
                        });
                });
            }
        });

        $subscriptionsQuery->where(function ($query) {
            $query->whereNull('expiration_date')
                ->orWhere('expiration_date', '>=', Carbon::create());
        });

        return $subscriptionsQuery->get();
    }
}

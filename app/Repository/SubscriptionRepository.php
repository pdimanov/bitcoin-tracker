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
        return Subscription::query()
            ->where(function ($query) {
                $sixHoursAgo = Carbon::now()->addHours(-6);
                $query->whereNull('last_notified')
                    //Avoid spamming
                    ->orWhere('last_notified', '<=', $sixHoursAgo);
            })
            ->where(function ($query) use ($bitcoinDtos) {
                $query->whereNotNull('percentage')
                    ->orWhere(function ($query) use ($bitcoinDtos) {
                        $query->whereNull('percentage')
                            ->where(function ($query) use ($bitcoinDtos) {
                                /** @var BitcoinDto $bitcoinDto */
                                foreach ($bitcoinDtos as $bitcoinDto) {
                                    $query->orWhere(function ($query) use ($bitcoinDto) {
                                        $query->where('currency', $bitcoinDto->currency)
                                            ->where('price', '<=', $bitcoinDto->price);
                                    });
                                }
                            });
                    });
            })
            ->get();
    }
}

<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Notifications\SubscriptionTargetReached;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class ProcessSubscriptionNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Subscription $subscription
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->subscription->notify(new SubscriptionTargetReached($this->subscription));
        $this->subscription->update(['last_notified' => Carbon::now()]);
    }
}

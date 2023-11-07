<?php

namespace App\Http\Controllers;

use App\Enum\Currency;
use App\Enum\TimeInterval;
use App\Http\Requests\StoreNotificationRequest;
use App\Models\Subscription;
use App\Service\Parser\SubscriptionParserInterface;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $intervals = [];
        foreach (TimeInterval::cases() as $case) {
            $intervals[$case->value] = $case->label();
        }

        return Inertia::render('Subscription/Index', [
            'currencies' => Currency::cases(),
            'intervals'  => $intervals
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNotificationRequest $request, SubscriptionParserInterface $parser)
    {
        $data = $request->validated();

        $parsedData = $parser->parse($data);
        Subscription::create($parsedData);
    }
}

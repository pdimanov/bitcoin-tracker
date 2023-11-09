<?php

namespace App\Http\Controllers;

use App\Enum\Currency;
use App\Enum\TimeInterval;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Subscription;
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
    public function store(StoreSubscriptionRequest $request)
    {
        Subscription::create($request->validated());
    }
}

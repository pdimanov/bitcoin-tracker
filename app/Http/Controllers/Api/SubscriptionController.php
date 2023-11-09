<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function store(StoreSubscriptionRequest $request)
    {
        Subscription::create($request->validated());

        return response()->json([
            'message' => 'Successfully created'
        ]);
    }
}

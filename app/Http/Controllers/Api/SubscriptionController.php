<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Subscription;
use App\Service\Parser\SubscriptionParserInterface;

class SubscriptionController extends Controller
{
    public function store(StoreSubscriptionRequest $request, SubscriptionParserInterface $parser)
    {
        $data = $request->validated();

        $parsedData = $parser->parse($data);
        Subscription::create($parsedData);

        return response()->json([
            'message' => 'Successfully created'
        ]);
    }
}

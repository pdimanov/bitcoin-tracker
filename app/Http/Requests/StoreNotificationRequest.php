<?php

namespace App\Http\Requests;

use App\Enum\Currency;
use App\Enum\TimeInterval;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNotificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isPercentageBased = $this->input('isPercentageBased');

        if ($isPercentageBased) {
            return $this->mergeWithCommonRules([
                'pricePercentage' => 'required|integer|numeric',
                'interval'        => ['required', Rule::enum(TimeInterval::class)]
            ]);
        }

        return $this->mergeWithCommonRules([
            'price' => 'required|integer|numeric|min:1',
        ]);
    }

    private function mergeWithCommonRules(array $rules): array
    {
        return array_merge([
            'email'             => 'required|email',
            'currency'          => ['required', Rule::enum(Currency::class)],
            'isPercentageBased' => 'boolean'
        ], $rules);
    }
}

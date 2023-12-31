<?php

namespace App\Http\Requests;

use App\Enum\Currency;
use App\Enum\TimeInterval;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubscriptionRequest extends FormRequest
{
    public function prepareForValidation()
    {
        if (!empty($this->input('currency'))) {
            $this->merge([
                'currency' => strtoupper($this->input('currency'))
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isPercentageBased = $this->input('isPercentageBased', false);

        if ($isPercentageBased) {
            return $this->mergeWithCommonRules([
                'percentage' => 'required|numeric',
                'interval'   => ['required', Rule::enum(TimeInterval::class)]
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
            'isPercentageBased' => 'required|boolean'
        ], $rules);
    }
}

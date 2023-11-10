<?php

namespace App\Http\Requests;

use App\Enum\Currency;
use App\Enum\TimePeriod;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class PriceHistoryPeriodRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }

    public function prepareForValidation()
    {
        if (!empty($this->input('currencies'))) {
            $this->merge([
                'currencies' => explode(',', strtoupper($this->input('currencies')))
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
        return [
            'timePeriod' => Rule::enum(TimePeriod::class),
            'timeShift'  => 'numeric|integer|min:0',
            'currencies' => $this->validateCurrencies(),
        ];
    }

    private function validateCurrencies()
    {
        return function ($attribute, $value, $fail) {
            $validCurrencies = Currency::getAllValues();
            foreach ($value as $currency) {
                if (!in_array($currency, $validCurrencies)) {
                    $fail("The $attribute contains invalid values.");
                }
            }
        };
    }
}

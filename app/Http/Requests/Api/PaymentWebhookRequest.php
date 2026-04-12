<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PaymentWebhookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data'              => ['required', 'array'],
            'data.reference'    => ['required', 'string', 'max:128'],
            'data.status'       => ['required', 'string', 'max:32'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'account_type_id' => 'required|exists:account_types,id',
            'balance' => 'numeric|min:0',
            'currency' => 'required|string|size:3',

            'has_overdraft' => 'boolean',
            'is_premium' => 'boolean',
            'has_insurance' => 'boolean',
        ];
    }

}

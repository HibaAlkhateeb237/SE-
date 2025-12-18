<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'amount'     => ['required', 'numeric', 'min:0.01'],
        ];
    }


    public function messages(): array
    {
        return [
            'account_id.required' => 'Account is required.',
            'account_id.exists'   => 'Account does not exist.',
            'amount.required'     => 'Amount is required.',
            'amount.min'          => 'Amount must be greater than zero.',
        ];
    }













}

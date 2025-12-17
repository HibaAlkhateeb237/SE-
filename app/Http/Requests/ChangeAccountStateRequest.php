<?php

namespace App\Http\Requests;

use App\Http\Responses\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ChangeAccountStateRequest extends FormRequest
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
            'state' => 'required|in:active,frozen,suspended,closed',
        ];
    }


    public function accountId(): int
    {
        return (int) $this->route('id');
    }


    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException(
            $validator,
            ApiResponse::error('Validation error', $validator->errors(), 422)
        );
    }





}

<?php

namespace App\Http\Requests;

use App\Http\Responses\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CompleteProfileRequest extends FormRequest
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
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'name' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $response = ApiResponse::error(
            'Validation error',
            $validator->errors(),
            422
        );

        throw new ValidationException($validator, $response);
    }





}

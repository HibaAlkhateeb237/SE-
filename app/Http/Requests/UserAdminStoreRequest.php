<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAdminStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true; // خليها true لأن الحماية عندك على مستوى route
    }

    public function rules()
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ];
    }
}

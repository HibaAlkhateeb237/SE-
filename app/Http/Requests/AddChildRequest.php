<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddChildRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'child_id' => 'required|exists:accounts,id',
        ];
    }
}

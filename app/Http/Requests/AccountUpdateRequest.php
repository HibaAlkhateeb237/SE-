<?php
class AccountUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'account_type_id' => 'sometimes|exists:account_types,id',
            'has_overdraft'   => 'sometimes|boolean',
            'is_premium'      => 'sometimes|boolean',
            'has_insurance'   => 'sometimes|boolean',
        ];
    }
}

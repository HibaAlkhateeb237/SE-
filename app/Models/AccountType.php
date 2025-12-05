<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'rules',
    ];

    protected $casts = [
        'rules' => 'array',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}

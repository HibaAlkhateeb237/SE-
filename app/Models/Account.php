<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'parent_id',
        'account_type_id',
        'currency',
        'balance',
        'state',
        'metadata',
        'has_overdraft',
'is_premium','has_insurance'];

    protected $casts = [
        'metadata' => 'array',
        'balance' => 'decimal:4',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'tx_id',
        'account_id',
        'type',
        'amount',
        'currency',
        'meta',
        'status',
        'initiated_by',
        'related_account_id',
    ];



    protected $casts = [
        'amount' => 'decimal:4',
        'meta' => 'array',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function relatedAccount()
    {
        return $this->belongsTo(Account::class, 'related_account_id');
    }


}

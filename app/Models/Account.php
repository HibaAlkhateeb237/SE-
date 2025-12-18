<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\States\Account\ActiveState;
use App\States\Account\FrozenState;
use App\States\Account\SuspendedState;
use App\States\Account\ClosedState;
use App\States\Account\AccountState;

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






   // private AccountState $stateInstance;

    public function getStateInstance(): AccountState
    {
        return match ($this->state) {
            'active' => new ActiveState(),
            'frozen' => new FrozenState(),
            'suspended' => new SuspendedState(),
            'closed' => new ClosedState(),
            default     => new ActiveState(),
        };
    }

    public function changeState(string $state)
    {
        $this->state = $state;
        $this->save();
    }

    // Business methods using the State Pattern
    public function deposit(float $amount)
    {
        $this->getStateInstance()->deposit($this, $amount);
    }

    public function withdraw(float $amount)
    {
        $this->getStateInstance()->withdraw($this, $amount);
    }

    public function freeze()
    {
        $this->getStateInstance()->freeze($this);
    }

    public function activate()
    {
        $this->getStateInstance()->activate($this);
    }

    public function suspend()
    {
        $this->getStateInstance()->suspend($this);
    }

    public function close()
    {
        $this->getStateInstance()->close($this);
    }



    public function relatedTransactions()
    {
        return $this->hasMany(Transaction::class, 'related_account_id');
    }






}

<?php

namespace App\Http\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function update(User $user, array $data)
    {
        return $user->update($data);
    }
}

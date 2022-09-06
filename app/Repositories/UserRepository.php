<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;

class UserRepository implements UserInterface
{
    public function getUserByEmail($email) 
    {
        return User::where('email', $email)->first();
    }
}
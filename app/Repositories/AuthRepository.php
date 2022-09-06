<?php

namespace App\Repositories;

use App\Interfaces\AuthInterface;
use App\Models\User;
use App\Traits\ResponseApi;

class AuthRepository implements AuthInterface
{
    use ResponseApi;

    public function createUser($input)
    {
        $user = User::create($input);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }

    public function loginUser($input)
    {
        $user = User::where('email', $input['email'])->first();

        return response()->json([
            'status' => true,
            'message' => 'User logged in successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }
}
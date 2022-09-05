<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Interfaces\AuthInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $authInterface;

    public function __construct(AuthInterface $authInterface)
    {
        $this->authInterface = $authInterface;
    }
    /**
     * Create user
     * @param Request $request
     * @return User
     */
    public function createUser(RegisterRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);

        return $this->authInterface->createUser($input);
    }

    public function loginUser(LoginRequest $request)
    {
        $input = $request->all();

        if(!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Email/password do not match our records.'
            ], 401);
        };

        return $this->authInterface->loginUser($input);
    }
}

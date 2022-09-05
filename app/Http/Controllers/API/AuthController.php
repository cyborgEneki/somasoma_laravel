<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Interfaces\AuthInterface;
use App\Models\User;
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
    public function createUser(UserRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);

        $this->authInterface->createUser($input);
    }
}

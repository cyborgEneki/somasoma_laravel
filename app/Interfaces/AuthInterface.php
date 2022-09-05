<?php

namespace App\Interfaces;

interface AuthInterface
{
    public function createUser($input);
    public function loginUser($input);
}

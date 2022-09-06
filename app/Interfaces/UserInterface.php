<?php

namespace App\Interfaces;

interface UserInterface
{
    public function getUserByEmail($email);
}
<?php

namespace App\Interfaces;

interface BookInterface {
    public function createBook(int $id = null, array $input);
}
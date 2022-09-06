<?php

namespace App\Interfaces;

interface BookInterface
{
    public function storeBook($input);
    public function updateBook($input, $id);
}

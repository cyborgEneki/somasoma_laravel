<?php

namespace App\Interfaces;

interface BookInterface
{
    public function storeBook($input, $id = null);
}

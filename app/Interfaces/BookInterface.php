<?php

namespace App\Interfaces;

interface BookInterface
{
    public function storeBook($input, $id = null);
    public function changeFile($details, $file);
}

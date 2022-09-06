<?php

namespace App\Interfaces;

interface BookInterface
{
    public function storeBook($input, $id = null);
    public function changeBook($input);
    public function changeBookJacket($input);
}

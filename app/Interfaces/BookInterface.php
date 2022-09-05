<?php

namespace App\Interfaces;

use App\Http\Requests\BookRequest;

interface BookInterface {
    public function createBook(BookRequest $request, $id = null);
}
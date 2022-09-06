<?php

namespace App\Http\Controllers;

class BookController extends Controller
{
    public function create()
    {
        return view('books.create');
    }
}

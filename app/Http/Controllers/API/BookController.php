<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Interfaces\BookInterface;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    private $bookInterface;

    public function __construct(BookInterface $bookInterface)
    {
        $this->bookInterface = $bookInterface;
    }

    public function store(BookRequest $request, $id = null)
    {
        $input = $request->all();

        $input['user_id'] = auth()->id();

        dd($input['book']->getClientOriginalExtension());

        $input['file_type'] = $input['book']->getClientOriginalExtension();

        return $this->bookInterface->storeBook($input, $id);
    }
}

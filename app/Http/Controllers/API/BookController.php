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

        $path = Storage::disk('s3')->put('books', $request->book);
        $input['book_url'] = Storage::disk('s3')->url($path);

        return $this->bookInterface->storeBook($input, $id);
    }
}

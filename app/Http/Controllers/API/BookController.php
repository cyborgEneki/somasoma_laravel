<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Interfaces\BookInterface;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private $bookInterface;

    public function __construct(BookInterface $bookInterface)
    {
        $this->bookInterface = $bookInterface;
    }

    public function store(BookRequest $request, $id = null)
    {
        dd('test');
        $input = $request->all();
        // $input['user_id'] = auth()->id;
        // $input['img_url'] = ;
        // $input['file_url'] = ;

        // Add genres and validate

        $book = $this->bookInterface->createBook($id, $input);

        if (!$book) {
            // return response()->json([
            //     'message' => 'No user found'
            //     'error' => true
            // ], 404);
        } else {
            return response()->json([
                'message' => 'User detail',
                'code' => 200,
                'error' => false,
                'results' => $book
            ], 200);
        }
    }
}

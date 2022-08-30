<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private $bookRepository;

    public function __construct()
    {
        $this->bookRepository = new BookInterface();
    }

    public function store(Request $request, $id = null)
    {
        $this->validate($request, [
            'name' => 'required',
            'author' => 'required',
            'password' => 'required'
        ]);

        $input = request()->all();
        // $input['user_id'] = auth()->id;
        // $input['img_url'] = ;
        // $input['file_url'] = ;

        // Add genres and validate

        $book = $this->bookRepository->createBook($id, $input);

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

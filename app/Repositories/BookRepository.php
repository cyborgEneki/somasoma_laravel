<?php

namespace App\Repositories;

use App\Http\Requests\BookRequest;
use App\Interfaces\BookInterface;
use App\Models\Book;
use App\Traits\ResponseApi;

class BookRepository implements BookInterface
{
    use ResponseApi;

    public function createBook(BookRequest $request, $id = null)
    {
        $input = $request->all();

        if ($id) {
            $book = Book::find($id);

            if (!$book) {
                return $this->error('No book with ID ' . $id, 404);
            }

            $book->update($input);
        } else {
            $book = Book::create($input);
        }

        return $this->success($id ? 'Book created' : 'Book updated', $book, $id ? 200 : 201);
    }
}

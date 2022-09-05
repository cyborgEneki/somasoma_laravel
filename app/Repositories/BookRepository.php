<?php

namespace App\Repositories;

use App\Interfaces\BookInterface;
use App\Models\Book;
use App\Traits\ResponseApi;

class BookRepository implements BookInterface
{
    use ResponseApi;

    public function storeBook($input, $id = null)
    {
        if ($id) {
            $book = Book::find($id);

            if (!$book) {
                return $this->error('No book with ID ' . $id, 404);
            }

            $book->update($input);
        } else {
            $book = Book::create($input);
        }

        $book->genres()->sync($input['genreIds']);

        return $this->success($id ? 'Book updated' : 'Book created', $book, $id ? 204 : 201);
    }
}

<?php

use App\Interfaces\BookInterface;
use App\Models\Book;

class BookRepository implements BookInterface
{
    public function createBook($id = null, array $input)
    {
        if ($id) {
            $book = Book::find($id);

            if (!$book) {
                return false;
            }

            return $book->update($input);
        } else {
            return Book::create($input);
        }
    }
}

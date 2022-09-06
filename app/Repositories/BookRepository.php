<?php

namespace App\Repositories;

use App\Interfaces\BookInterface;
use App\Models\Book;
use App\Traits\ResponseApi;
use Illuminate\Support\Facades\Storage;

class BookRepository implements BookInterface
{
    use ResponseApi;

    public function storeBook($input)
    {
        $input['book_jacket_url'] = $this->saveBookJacketToStorage($input['book_jacket']);
        $input['book_url'] = $this->saveBookToStorage($input['book']);

        $book = Book::create($input);
        $book->genres()->sync($input['genreIds']);

        return $this->success('Book created', $book, 201);
    }

    public function updateBook($input, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return $this->error('No book with ID ' . $id, 404);
        }

        $oldBookUrl = $book->book_url;

        $input['book_url'] = $this->saveBookToStorage($input['book']);

        $book->update($input);

        Storage::disk('s3')->delete($oldBookUrl);

        $book->genres()->sync($input['genreIds']);

        return $this->success($id ? 'Book updated' : 'Book created', $book, $id ? 204 : 201);
    }

    private function saveBookToStorage($file)
    {
        return Storage::disk('s3')->put('books', $file);
    }

    private function saveBookJacketToStorage($file)
    {
        return Storage::disk('s3')->put('book_jacket', $file);
    }
}

// delete/replace when edited
// how to identify a book that's repeated
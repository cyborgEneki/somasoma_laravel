<?php

namespace App\Repositories;

use App\Interfaces\BookInterface;
use App\Models\Book;
use App\Traits\ResponseApi;
use Illuminate\Support\Facades\Storage;

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

            $oldBookUrl = $book->book_url;

            $input['book_url'] = $this->saveBookToStorage($input['book']);

            $book->update($input);

            Storage::disk('s3')->delete($oldBookUrl);
        } else {
            $input['book_url'] = $this->saveBookToStorage($input['book']);

            $book = Book::create($input);
        }

        $book->genres()->sync($input['genreIds']);

        return $this->success($id ? 'Book updated' : 'Book created', $book, $id ? 204 : 201);
    }
    
    private function saveBookToStorage($file)
    {
        return Storage::disk('s3')->put('books', $file);
    }
}
// delete/replace when edited
// how to identify a book that's repeated
// max folder size
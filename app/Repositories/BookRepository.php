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

            $oldBookName = $book->original_file_name;
            $newBookName = $input['book']->getClientOriginalName();

            if ($this->hasFileNameChanged($oldBookName, $newBookName)) {
                $oldBookUrl = $book->book_url;

                $this->deleteFileFromStorage($oldBookUrl);

                $input['original_file_name'] = $input['book']->getClientOriginalName();
                $input['book_url'] = $this->saveFileToStorage('books', $input['book']);
            }

            if ($book->original_book_jacket_name != $input['book_jacket']->getClientOriginalName()) {
                $oldBookJacketUrl = $book->book_jacket_url;

                $this->deleteFileFromStorage($oldBookJacketUrl);

                $input['original_book_jacket_name'] = $input['book_jacket']->getClientOriginalName();
                $input['book_jacket_url'] = $this->saveFileToStorage('book_jackets', $input['book_jacket']);
            }

            $book->update($input);
        } else {
            $input['book_jacket_url'] = $this->saveFileToStorage('book_jackets', $input['book_jacket']);
            $input['book_url'] = $this->saveFileToStorage('books', $input['book']);

            $input['original_book_jacket_name'] = $input['book_jacket']->getClientOriginalName();
            $input['original_file_name'] = $input['book']->getClientOriginalName();

            $book = Book::create($input);
        }

        $book->genres()->sync($input['genreIds']);

        return $this->success($id ? 'Book updated' : 'Book created', $book, $id ? 204 : 201);
    }

    private function hasFileNameChanged($oldName, $newName)
    {
        return $oldName != $newName;
    }

    private function deleteFileFromStorage($oldUrl)
    {
        Storage::disk('s3')->delete($oldUrl);
    }

    private function saveFileToStorage($folderName, $file)
    {
        return Storage::disk('s3')->put($folderName, $file);
    }
}

// delete/replace when edited
// how to identify a book that's repeated
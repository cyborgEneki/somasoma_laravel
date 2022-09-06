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

            $book->update($input);
        } else {
            $this->getStorageFileUrls($input);

            $book = Book::create($input);
        }

        $book->genres()->sync($input['genreIds']);

        return $this->success($id ? 'Book updated' : 'Book created', $book, $id ? 200 : 201);
    }

    private function getStorageFileUrls(&$input)
    {
        $input['book_jacket_url'] = $this->saveFileToStorage('book_jackets', $input['book_jacket']);
        $input['book_url'] = $this->saveFileToStorage('books', $input['book']);
    }

    private function saveFileToStorage($folderName, $file)
    {
        return Storage::disk('s3')->put($folderName, $file);
    }

    public function changeBook($input)
    {
        // if ($this->hasFileNameChanged($oldBookName, $newBookName)) {
        //     $oldBookUrl = $book->book_url;

        //     $this->deleteFileFromStorage($oldBookUrl);

        //     $input['original_file_name'] = $input['book']->getClientOriginalName();
        //     $input['book_url'] = $this->saveFileToStorage('books', $input['book']);
        // }
    }

    public function changeBookJacket($input)
    {
        // if ($book->original_book_jacket_name != $input['book_jacket']->getClientOriginalName()) {
        //     $oldBookJacketUrl = $book->book_jacket_url;

        //     $this->deleteFileFromStorage($oldBookJacketUrl);

        //     $input['original_book_jacket_name'] = $input['book_jacket']->getClientOriginalName();
        //     $input['book_jacket_url'] = $this->saveFileToStorage('book_jackets', $input['book_jacket']);
        // }
    }

    private function deleteFileFromStorage($oldUrl)
    {
        Storage::disk('s3')->delete($oldUrl);
    }
}

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

    public function changeFile($details, $file)
    {
        $book = Book::find($details['id']);

        if (!$book) {
            return $this->error('No book with ID ' . $details['id'], 404);
        }

        if ($details['fieldName'] == 'book_url') {
            $oldUrl = $book->book_url;
            $input['book_url'] = $this->saveFileToStorage('books', $file);
        } else {
            $oldUrl = $book->book_jacket_url;
            $input['book_jacket_url'] = $this->saveFileToStorage('book_jackets', $file);
        }

        $this->deleteFileFromServer($oldUrl);

        $book->update($input);

        return $this->success($details['message'], $book, 204);
    }

    private function deleteFileFromServer($oldUrl)
    {
        Storage::disk('s3')->delete($oldUrl);
    }
}

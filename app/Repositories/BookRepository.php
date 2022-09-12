<?php

namespace App\Repositories;

use App\Interfaces\BookInterface;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class BookRepository implements BookInterface
{
    public function storeBook($input, $id = null)
    {
        if ($id) {
            $book = $this->findBookById($id);

            if (!$book) {
                return false;
            }

            $book->update($input);
        } else {
            $this->getStorageFileUrls($input);

            $book = Book::create($input);
        }

        $book->genres()->sync($input['genreIds']);

        return $book;
    }

    private function getStorageFileUrls(&$input)
    {
        if (isset($input['book_jacket'])) {
            $input['book_jacket_url'] = $this->saveFileToStorage('book_jackets', $input['book_jacket']);
        }
        $input['book_url'] = $this->saveFileToStorage('books', $input['book']);
    }

    private function saveFileToStorage($folderName, $file)
    {
        $diskLocation = config('database.disk');

        return Storage::disk($diskLocation)->put($folderName, $file);
    }

    public function changeFile($details, $file)
    {
        $book = $this->findBookById(details['id']);

        if (!$book) {
            return false;
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

        return $book;
    }

    private function deleteFileFromServer($oldUrl)
    {
        $diskLocation = config('database.disk');

        Storage::disk($diskLocation)->delete($oldUrl);
    }

    public function deleteBook($id)
    {
        $book = $this->findBookById($id);

        $this->deleteFileFromServer($book->book_url);
        $this->deleteFileFromServer($book->book_jacket_url);

        $book->genres()->detach();

        return $book->delete();
    }

    public function findBookById($id)
    {
        return Book::find($id);
    }

    public function getBooks()
    {
        return Book::with('genres')->get();
    }

    public function showBook($id)
    {
        return Book::where('id', $id)->with('genres')->first();
    }
}

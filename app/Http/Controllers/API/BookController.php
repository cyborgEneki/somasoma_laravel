<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookChangeFileRequest;
use App\Http\Requests\BookChangeJacketRequest;
use App\Http\Requests\BookRequest;
use App\Interfaces\BookInterface;
use App\Interfaces\FileTypeInterface;
use App\Traits\ResponseApi;

class BookController extends Controller
{
    use ResponseApi;

    private $bookInterface;
    private $fileTypeInterface;

    public function __construct(
        BookInterface $bookInterface,
        FileTypeInterface $fileTypeInterface
    ) {
        $this->bookInterface = $bookInterface;
        $this->fileTypeInterface = $fileTypeInterface;
    }

    public function storeBook(BookRequest $request, $id = null)
    {
        $input = $request->all();

        $input['user_id'] = auth()->id();

        if (!$id) {
            $input['file_type_id'] = $this->getFileTypeId($input['book']);
        }

        $book = $this->bookInterface->storeBook($input, $id);

        if (!$book) {
            return $this->error('No book with ID ' . $id, 404);
        }

        return $this->success($id ? 'Book updated' : 'Book created', $book, $id ? 200 : 201);
    }

    private function getFileTypeId($file)
    {
        $ext = strtolower($file->getClientOriginalExtension());

        $fileType = $this->fileTypeInterface->getFileTypeByName($ext);

        if (!$fileType) {
            $fileType = $this->createNewFileType($ext);
        }

        return $fileType->id;
    }

    private function createNewFileType($ext)
    {
        $ext = ['name' => $ext];

        return $this->fileTypeInterface->storeFileType($ext);
    }

    public function changeBook(BookChangeFileRequest $request, $id)
    {
        $file = $request->file('book');

        $details = [
            'id' => $id,
            'fieldName' => 'book_url',
            'message' => 'Book updated'
        ];

        $book = $this->bookInterface->changeFile($details, $file);

        $this->bookJsonResponse($book, $details);
    }

    public function changeBookJacket(BookChangeJacketRequest $request, $id)
    {
        $file = $request->file('book_jacket');

        $details = [
            'id' => $id,
            'fieldName' => 'book_jacket_url',
            'message' => 'Book jacket updated'
        ];

        $book = $this->bookInterface->changeFile($details, $file);

        $this->bookJsonResponse($book, $details);
    }

    private function bookJsonResponse($book, $details)
    {
        if (!$book) {
            return $this->error('No book with ID ' . $details['id'], 404);
        }

        return $this->success($details['message'], $book, 204);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Interfaces\BookInterface;
use App\Interfaces\FileTypeInterface;

class BookController extends Controller
{
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
        
        $input['file_type_id'] = $this->getFileTypeId($input['book']);

        return $this->bookInterface->storeBook($input, $id);
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

    public function changeBookFile(BookRequest $request, $id) // diff form request
    {
        //specifically for changing book
    }
}

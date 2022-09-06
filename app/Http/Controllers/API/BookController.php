<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Interfaces\BookInterface;
use App\Interfaces\FileTypeInterface;
use Illuminate\Support\Facades\Storage;

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

    public function storeBook(BookRequest $request, $id = null) // adapt form request
    {
        $input = $request->all();
dd($input);
        $input['user_id'] = auth()->id();
        
        $input['file_type_id'] = $this->getFileTypeId($input['book']);

        return $this->bookInterface->storeBook($input, $id);
    }

    private function getFileTypeId($file)
    {
        $fileExtension = strtolower($file->getClientOriginalExtension());

        $fileType = $this->fileTypeInterface->getFileTypeByName($fileExtension);

        if (!$fileType) {
            $ext = ['name' => $fileExtension];

            $fileType = $this->fileTypeInterface->storeFileType($ext);
        }

        return $fileType->id;
    }

    public function changeBookFile(BookRequest $request, $id) // diff form request
    {
        //specifically for changing book
    }
}

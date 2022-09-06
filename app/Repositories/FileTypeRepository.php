<?php

namespace App\Repositories;

use App\Interfaces\FileTypeInterface;
use App\Models\FileType;

class FileTypeRepository implements FileTypeInterface
{
    public function storeFileType($type) 
    {
        return FileType::create($type);
    }

    public function getFileTypeByName($name)
    {
        return FileType::where('name', $name)->first();
    }
}
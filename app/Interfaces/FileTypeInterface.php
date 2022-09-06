<?php

namespace App\Interfaces;

interface FileTypeInterface
{
    public function storeFileType($type);
    public function getFileTypeByName($name);
}
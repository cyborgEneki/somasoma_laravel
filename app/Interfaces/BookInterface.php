<?php

namespace App\Interfaces;

interface BookInterface
{
    public function storeBook($input, $id = null);
    public function changeFile($details, $file);
    public function deleteBook($id);
    public function findBookById($id);
}

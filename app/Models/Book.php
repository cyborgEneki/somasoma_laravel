<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $table = 'books';

    protected $fillable = [
        'name',
        'author',
        'book_url',
        'book_jacket_url',
        'user_id',
        'file_type_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fileType()
    {
        return $this->belongsTo(FileType::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }
}

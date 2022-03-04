<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'isbn',
        'authors',
        'country',
        'number_of_pages',
        'publisher',
        'release_date',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'release_date' => 'datetime:Y-m-d',
        'authors' => 'array',
    ];
}

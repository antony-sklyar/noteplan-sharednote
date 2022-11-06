<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublishedNote extends Model
{
    protected $fillable = [
        'title',
        'content',
        'theme',
    ];

    protected $guarded = [
        'guid',
        'access_key',
    ];
}

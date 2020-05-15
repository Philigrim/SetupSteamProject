<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'author_id', 'author','title', 'text'
    ];
}

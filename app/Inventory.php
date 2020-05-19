<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name', 'quantity', 'quantity_left', 'steam_center_id', 'room_id'
    ];
}

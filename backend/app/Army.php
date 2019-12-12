<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Army extends Model
{
    protected $table = 'armies';

    protected $fillable = [
        'name',
        'units',
        'strategy'
    ];
}

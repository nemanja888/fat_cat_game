<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Army extends Model
{
    protected $table = 'armies';

    protected $fillable = [
        'name',
        'units',
        'strategy',
        'game_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}

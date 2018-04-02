<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cell extends Model
{

    protected $fillable = [
        'selected', 'sign', 'num', 'game_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function game()
    {
        return $this->belongsTo('App\Game');
    }
}

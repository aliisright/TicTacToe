<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameHistory extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

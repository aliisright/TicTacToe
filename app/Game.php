<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{

    protected $fillable = [
        'won', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function cells()
    {
        return $this->hasMany('App\Cell');
    }
}

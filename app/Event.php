<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    public $table = 'events';

    protected $dates = [
        'starts_at',
        'ends_at',
    ];

    public function talks()
    {
        return $this->hasMany(Talk::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}



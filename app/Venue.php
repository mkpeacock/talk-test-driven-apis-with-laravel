<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use SoftDeletes;

    public $table = 'venues';

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}

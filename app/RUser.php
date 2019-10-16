<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RUser extends Model
{
    public function posts()
    {
        return $this->hasMany('App\Posts');
    }
}

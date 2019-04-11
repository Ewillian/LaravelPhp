<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public function owner(){
        return $this->belongTo('App\Company', 'user_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public function owner(){
        return $this->belongTo('App\Company', 'user_id');
    }



    public function user(){
        return $this->belongsToMany('App\User', 'userpost', 'job_id', 'user_id');
    }
}

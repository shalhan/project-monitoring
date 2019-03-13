<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityCommittee extends Model
{
    protected $fillable = ['activity_id','user_id'];

    public function activity() {
        return $this->belongsTo('App\Activity');
    }

    public function user() {
        return $this->belongsTo('App\User')->select('id', 'name', 'type_id');
    }
}

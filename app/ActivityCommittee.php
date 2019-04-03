<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ActivityCommittee extends Model
{
    protected $fillable = ['activity_id','user_id'];

    public function activity() {
        return $this->belongsTo('App\Activity');
    }

    public function user() {
        return $this->belongsTo('App\User')->select('id', 'name', 'type_id', 'code_name');
    }

    public function getByUserId($userId) {
        return $this->where('user_id', $userId)
                    ->get();
    }

    public function create($activityId, $data) {
        $this->dropByActivityId($activityId);
        foreach($data as $id) {
            $this->user_id = $id;
            $this->activity_id = $activityId;
            $this->save();
        }
    }

    public function dropByActivityId($activityId) {
        if(Auth::check()) {
            $this->where('activity_id', $activityId)->delete();
        }
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\ActivityCommittee;

class Note extends Model
{
    protected $fillable = ['name', 'from', 'to', 'created_by', 'activity_id'];

    public function create($data) {
        if(Auth::check()) {
            $this->name = $data['name'];
            $this->from = $data['fromDateTime'];
            $this->to = $data['toDateTime'];
            $this->created_by = Auth::user()->id;
            $this->activity_id = $data['activity_id'];
            $this->save();
        }
    }

    public function drop($id) {
        if(Auth::check()) {
            $note = $this->find($id);
            if(Auth::user()->id === $note->created_by)
                $note->delete();
        }
    }

    public function getByCreatedBy($createdId) {
        return $this->where('created_by', $createdId)
                    ->with(['activity','createdBy'])
                    ->orderBy('created_at', 'asc')
                    ->get();
    }

    public function getByActivityIds($activityIds) {
        return $this->whereIn("activity_id", $activityIds)
                    ->with(['activity','createdBy'])
                    ->orderBy('created_at', 'asc')
                    ->get();
    }

    public function getAllUserNotes($userId) {
        $ac = new ActivityCommittee();
        $userActivities = $ac->getByUserId($userId);
        $activityIds = collect();
        foreach($userActivities as $userActivity) {
            $activityIds->push($userActivity->activity_id);
        }
        return $this->getByActivityIds($activityIds);
    }

    public function activity() {
        return $this->belongsTo('App\Activity');
    }

    public function createdBy() {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }
}

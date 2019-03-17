<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\ActivityCommittee;
use Auth;

class Activity extends Model
{
    protected $appends = ['isLecture'];
    protected $fillable = ['name', 'location', 'from', 'to', 'user_id', 'file'];

    public function getAll() {
        return $this->select('id', 'name', 'from', 'to')
                    ->with(['activityCommittees', 'notes'])
                    ->get();
    }

    public function create($data) {
        $this->name = $data['name'];
        $this->location = $data['location'];
        $this->from = $data['fromDateTime'];
        $this->to = $data['toDateTime'];
        $this->save();
        //tambah panitia
        foreach($data['user_id'] as $id) {
            $activityCommittee = new ActivityCommittee();
            $activityCommittee->user_id = $id;
            $activityCommittee->activity_id = $this->id;
            $activityCommittee->save();
        }
        //tambah file
        if(isset($data['file']))
        {
            $filePath  = 'activity_files/' . $this->id . '/' . date("YmdHis") . '.' . $data['file']->getClientOriginalExtension();
            Storage::putFileAs('', $data['file'], $filePath);
            $this->file = $filePath;
            $this->update();
        }
    }

    public function getAllActivityCalendar() {
        $activities = $this->getAll();
        $data = collect(['id','title', 'start', 'end', 'backgroundColor', 'borderColor', 'isLecture']);
        $result = collect();
        foreach($activities as $activity) {
            $color = $activity->isLecture ? '#f39c12' : 'grey';
            $combined = $data->combine([$activity->id, $activity->name, $activity->from, $activity->to, $color, $color, $activity->isLecture ]);
            $result->push($combined);
            if(Auth::check() && isset($activity->notes) && $activity->isLecture) {
                foreach($activity->notes as $n) {
                    $color = Auth::user()->id === $n->created_by ? '#27ae60' : '#3498db';
                    $title = Auth::user()->id === $n->created_by ? $n->name . ' (Yours)' : $n->name;
                    $combined  = $data->combine([$n->id, $title, $n->from, $n->to, $color, $color, false ]);
                    $result->push($combined);
                }
            }
        }
        return $result;
    }

    public function activityCommittees() {
        return $this->hasMany('App\ActivityCommittee')->select('user_id', 'activity_id')->with('user');
    }

    public function getIsLectureAttribute() {
        if(!Auth::check())
            return false;
        $committees = $this->activityCommittees;
        foreach($committees as $committee) {
            if($committee->user_id === Auth::user()->id)
                return true;
        }
        return false;
    }

    public function notes() {
        return $this->hasMany('App\Note');
    }
}

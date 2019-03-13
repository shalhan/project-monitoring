<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\ActivityCommittee;
use Auth;

class Activity extends Model
{
    protected $appends = ['isLecture'];
    protected $fillable = ['name', 'location', 'from', 'to', 'time', 'user_id', 'file'];

    public function getAll() {
        return $this->select('id', 'name', 'from', 'to', 'time')
                    ->with('activityCommittees')
                    ->get();
    }

    public function create($data) {
        $this->name = $data['name'];
        $this->location = $data['location'];
        $this->from = $data['from'];
        $this->to = $data['to'];
        $this->time = $data['time'];
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
        $data = collect(['title', 'start', 'end', 'backgroundColor', 'borderColor']);
        $result = collect();
        foreach($activities as $activity) {
            $color = $activity->isLecture ? '#f39c12' : 'grey';
            $combined = $data->combine([$activity->name, $activity->from, $activity->to, $color, $color ]);
            $result->push($combined);
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
}

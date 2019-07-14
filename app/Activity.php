<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\ActivityCommittee;
use Auth;
use Carbon\Carbon;


class Activity extends Model
{
    protected $fillable = ['name', 'location', 'from', 'to', 'user_id', 'file'];

    public function getById($id) {
        return $this->find($id);
    }

    public function getAll($status = 'all') {
        return $this->select('id', 'name', 'from', 'to', 'location')
                    ->where(function($query) use ($status) {
                        if($status === 'upcoming')
                        $syntax = '>=';
                        else if($status === 'end')
                            $syntax = '<';
                        else
                            return;
                        $query->where('from', $syntax, date('Y-m-s H:i:s'));
                    })
                    ->with('activityCommittees')
                    ->orderBy('from', 'asc')
                    ->get();
    }

    public function getAllLectureActivities() {
        $activitiesUpcoming = $this->getAll('upcoming');
        $activitiesEnd = $this->getAll('end');
        
        if($activitiesEnd) 
            $merged = $activitiesUpcoming->merge($activitiesEnd);
        else
            $merged = $activitiesUpcoming;
            
        return $merged;
    }


    public function createOrUpdate($data) {
        $activity = $this->updateOrCreate(
            [ 'id' => $data['id'] ],
            [
                'name'=> $data['name'],
                'location' => $data['location'],
                'from' => $data['fromDateTime'],
                'to' => $data['toDateTime'],
            ]
        );

        //tambah panitia
        $activityCommittee = new ActivityCommittee;
        $activityCommittee->dropByActivityId($activity->id);
        foreach($data['user_id'] as $id) {
            $activityCommittee = new ActivityCommittee;
            $activityCommittee->user_id = $id;
            $activityCommittee->activity_id = $activity->id;
            $activityCommittee->save();
        }
        
        //tambah file
        if(isset($data['file']))
        {
            $filePath  = 'activity_files/' . $activity->id . '/' . date("YmdHis") . '.' . $data['file']->getClientOriginalExtension();
            Storage::putFileAs('', $data['file'], $filePath);
            $activity->file = $filePath;
            $activity->update();
        }
    }

    public function getAllActivityCalendar() {
        $activities = $this->getAll();
        $data = collect(['id','title', 'start', 'end', 'backgroundColor', 'borderColor', 'isLecture', 'location']);
        $result = collect();
        $id = 1;
        foreach($activities as $activity) {
            $color = $activity->isLecture ? '#f39c12' : 'grey';
            $userCodeName = '';
            $length = count($activity->activityCommittees);
            foreach($activity->activityCommittees as $key => $committee) {
                $seperator = (int) $length-1 === $key ? '' : ', ';
                $userCodeName .= $committee->user->code_name . $seperator;
            }
            $combined = $data->combine([$id, $activity->name . ' (' . $userCodeName . ')', $activity->from, $activity->to, $color, $color, $activity->isLecture, $activity->location ]);
            $result->push($combined);
            $id++;
        }
        if(Auth::check() && isset(Auth::user()->notes)) {
            foreach(Auth::user()->notes as $n) {
                $color = Auth::user()->id === $n->created_by ? '#27ae60' : '#3498db';
                $title = Auth::user()->id === $n->created_by ? $n->name . ' (Catatan anda)' : $n->name;
                $combined  = $data->combine([$id, $title, $n->from, $n->to, $color, $color, false, $activity->location ]);
                $result->push($combined);
                $id++;
            }
        }

        return $result;
    }

    public function activityCommittees() {
        return $this->hasMany('App\ActivityCommittee')->select('user_id', 'activity_id')->with('user');
    }

    public function getActivityCommitteeIdsAttribute() {
        $result = [];
        if($this->activityCommittees) {
            foreach($this->activityCommittees as $committee) {
                array_push($result, $committee->user->id);
            }
        }

        return $result;
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

    public function drop($id) {
        if(Auth::check()) {
            $note = $this->find($id);
            $note->delete();
        }
    }

    public function getIsEndAttribute() {
        if(strtotime($this->from) < strtotime(date('Y-m-d H:i:s')))
            return true;
        else
            return false;
    }

    public function getStartTimeAttribute() {
        return date('F d, Y | H:i', strtotime($this->from)) . ' WIB';
    }

    public function fromDate($format = "Y-m-d") {
        return date($format,strtotime(explode(" ", $this->from)[0]));
    }


    public function fromTime($format = "H:i:s") {
        return date($format, strtotime(explode(" ", $this->from)[1]));
    }

    public function toDate($format = "Y-m-d") {
        return date($format,strtotime(explode(" ", $this->to)[0]));
    }

    public function toTime($format = "H:i:s") {
        return date($format, strtotime(explode(" ", $this->to)[1]));
    }
}

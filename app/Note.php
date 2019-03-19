<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\ActivityCommittee;
use Carbon\Carbon;

class Note extends Model
{
    protected $fillable = ['name', 'from', 'to', 'created_by'];

    public function create($data) {
        if(Auth::check()) {
            $this->name = $data['name'];
            $this->from = $data['fromDateTime'];
            $this->to = $data['toDateTime'];
            $this->created_by = Auth::user()->id;
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
                    ->orderBy('from', 'asc')
                    ->get();
    }

    public function activity() {
        return $this->belongsTo('App\Activity');
    }

    public function createdBy() {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function getStartTimeRemainingAttribute() {
        $now = Carbon::now();
        return $now->diffForHumans($this->from);
    }
}

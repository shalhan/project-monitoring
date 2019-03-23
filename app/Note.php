<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\ActivityCommittee;
use Carbon\Carbon;

class Note extends Model
{
    protected $fillable = ['name', 'from', 'to', 'created_by', 'location'];

    public function create($data) {
        if(Auth::check()) {
            $this->name = $data['name'];
            $this->from = $data['fromDateTime'];
            $this->to = $data['toDateTime'];
            $this->created_by = Auth::user()->id;
            $this->location = $data['location'];
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

    public function getByCreatedBy($createdId, $status = 'all') {

        return $this->where('created_by', $createdId)
                    ->where(function($query) use ($status) {
                        if($status === 'upcoming')
                        $syntax = '>=';
                        else if($status === 'end')
                            $syntax = '<';
                        else
                            return;
                        $query->where('from', $syntax, date('Y-m-s H:i:s'));
                    })
                    ->with(['activity','createdBy'])
                    ->orderBy('from', 'asc')
                    ->get();
    }

    public function getByCreatedByWithStatus($createdId) {
        $notesUpcoming = $this->getByCreatedBy($createdId, 'upcoming');
        $notesEnd = $this->getByCreatedBy($createdId, 'end');
        
        if($notesEnd) {
            foreach($notesEnd as $note) {
                \Log::info(json_encode($notesUpcoming));
                $notesUpcoming->push($note);
            }
        }


        return $notesUpcoming;
    }

    public function activity() {
        return $this->belongsTo('App\Activity');
    }

    public function createdBy() {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function getStartTimeRemainingAttribute() {
        $boringLanguage = 'en_Custom';
        $translator = \Carbon\Translator::get($boringLanguage);
        $translator->setTranslations([
            'second' => ':count detik |:count detik',
            'minute' => ':count menit |:count menit',
            'hour' => ':count jam |:count jam',
            'day' => ':count hari |:count hari',
            'month' => ':count bulan |:count bulan',
            'year' => ':count tahun |:count tahun',
        ]);

        $translator->setTranslations([
            'before' => function ($time) {
                return $time . ' lagi';
            },
            'after' => function ($time) {
                return $time . ' yang lalu';
            },
        ]);
        
        $now = Carbon::now( new \DateTimeZone('Asia/Jakarta'));
        return $now->locale($boringLanguage)->diffForHumans($this->from);
    }

    public function getIsEndAttribute() {
        if(strtotime($this->from) < strtotime(date('Y-m-d H:i:s')))
            return true;
        else
            return false;
    }
}

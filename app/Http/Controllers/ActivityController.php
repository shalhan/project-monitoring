<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\ActivityCommittee;
use App\User;
use App\Note;
use Validator;
use Auth;

class ActivityController extends Controller
{
    private $activity;
    public function __construct() {
        $this->activity = new Activity();
        $this->user = new User();
    }
    public function activityView()
    {
        $result = $this->activity->getAllActivityCalendar();
        $notes = new Note();
        $lectureNotes = Auth::check() ? $notes->getByCreatedBy(Auth::user()->id) : [];
        return view('pages.activity', compact(['result', 'lectureNotes']));
    }
    public function manageActivityView()
    {
        $lectures = $this->user->getLectures();
        return view('pages.manageActivity', compact('lectures'));
    }

    public function create(Request $req) {
        try{
            $messages = [
                'name.required' => 'Nama kegiatan harus diisi',
                'location.required' => 'Lokasi kegiatan harus diisi',
                'dateFrom.required' => 'Tanggal mulai kegiatan harus diisi',
                'timeFrom.required' => 'Waktu mulai kegiatan harus diisi',
                'dateTo.required' => 'Tanggal berakhir kegiatan harus diisi',
                'dateTo.after_or_equal:date' => 'Tanggal harus lebih besar dari tanggal mulai',
                'timeTo.required' => 'Waktu berakhir kegiatan harus diisi',
                'user_id.required' => 'Panitia pelaksana kegiatan harus dipilih',
                // 'file.mimes' => 'Tipe file harus jpeg, jpg, png',
                // 'file.size' => 'Maksimum ukuran file : 2040kb',
            ];
            $validator = Validator::make($req->all(), [
                'name' => 'required',
                'location' => 'required',
                'dateFrom' => 'required',
                'timeFrom' => 'required',
                'dateTo' => 'required|after_or_equal:date:dateFrom',
                'timeTo' => 'required',
                'user_id' => 'required',
                // 'file' => 'nullable|mimes:jpeg,png|max:2000',
            ], $messages);
            if ($validator->fails()) {
                return redirect('/tambah-kegiatan')
                            ->withErrors($validator)
                            ->withInput();
            }
            //change date format
            $explodeDateFrom = explode("/", $req->dateFrom);
            $explodeDateTo = explode("/", $req->dateTo);
            $fromDateTime = $explodeDateFrom[2] . '-' . $explodeDateFrom[0] . '-' . $explodeDateFrom[1] . " " . date("H:i:s", strtotime($req->timeFrom));
            $toDateTime = $explodeDateTo[2] . '-' . $explodeDateTo[0] . '-' . $explodeDateTo[1] . " " . date("H:i:s", strtotime($req->timeTo));
            
            $data = [
                'name' => $req->name,
                'location' => $req->location,
                'fromDateTime' => $fromDateTime,
                'toDateTime' => $toDateTime,
                'file' => $req->file,
                'user_id' => $req->user_id
            ];
            $this->activity->create($data);
            return redirect()->back()->with('success', 'Menambahkan aktivitas berhasil');

        }
        catch(\Exception $e) {
            \Log::info($e->getMessage());
        }
    }
}

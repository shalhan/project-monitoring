<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\ActivityCommittee;
use App\User;
use App\Note;
use Validator;
use Auth;
use Carbon\Carbon;

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
        return view('pages.activity', compact(['result']));
    }
    public function manageActivityView()
    {
        $lectures = $this->user->getLectures();
        $activities = $this->activity->getAllLectureActivities();
        return view('pages.manageActivity', compact(['lectures','activities']));
    }

    public function updateActivityView($id) {      
        $lectures = $this->user->getLectures();
        $activity = $this->activity->getById($id);
        return view('pages.updateActivity', compact(['lectures', 'activity']));
    }

    public function updateOrCreate($id = -99, Request $req) {
        try{
            $messages = [
                'name.required' => 'Nama kegiatan harus diisi',
                'location.required' => 'Lokasi kegiatan harus diisi',
                'dateFrom.required' => 'Tanggal mulai kegiatan harus diisi',
                'timeFrom.required' => 'Waktu mulai kegiatan harus diisi',
                'dateTo.required' => 'Tanggal berakhir kegiatan harus diisi',
                'timeTo.required' => 'Waktu berakhir kegiatan harus diisi',
                'user_id.required' => 'Panitia pelaksana kegiatan harus dipilih',
                // 'file.mimes' => 'Tipe file harus jpeg, jpg, png',
                // 'file.size' => 'Maksimum ukuran file : 2040kb',
            ];

            if(isset($req->dateTo) && isset($req->timeTo))
            {
                $explodeDateTo = explode("/", $req->dateTo);
                $toDateTime = $explodeDateTo[2] . '-' . $explodeDateTo[0] . '-' . $explodeDateTo[1] . " " . date("H:i:s", strtotime($req->timeTo));
            }
            else 
                $toDateTime = date('Y-m-d H:i:s');

            if(isset($req->dateFrom) && isset($req->timeFrom)) {
                $explodeDateFrom = explode("/", $req->dateFrom);
                $fromDateTime = $explodeDateFrom[2] . '-' . $explodeDateFrom[0] . '-' . $explodeDateFrom[1] . " " . date("H:i:s", strtotime($req->timeFrom));
            }
            else 
                $fromDateTime = date('Y-m-d H:i:s');

            $validator = Validator::make($req->all(), [
                'name' => 'required',
                'location' => 'required',
                'dateFrom' => ['required', 
                    function($attribute, $value, $fail) use ($fromDateTime) {
                        if(strtotime($fromDateTime) < strtotime(date("Y-m-d H:i:s")))
                            $fail("Tanggal dan jam harus lebih besar atau sama dengan hari ini");
                    }
                ],
                'timeFrom' => 'required',
                'dateTo' => ['required', 
                    function($attribute, $value, $fail) use ($fromDateTime, $toDateTime) {
                        if(strtotime($fromDateTime) > strtotime($toDateTime))
                            $fail("Tanggal dan jam selesai harus lebih besar atau sama dengan tanggal dan jam mulai");
                    }
                ],
                'timeTo' => 'required',
                'user_id' => 'required',
                // 'file' => 'nullable|mimes:jpeg,png|max:2000',
            ], $messages);

            if ($validator->fails()) {
                return redirect('/kelola-kegiatan')
                            ->withErrors($validator)
                            ->withInput();
            }
            $data = [
                'id' => $id,
                'name' => $req->name,
                'location' => $req->location,
                'fromDateTime' => $fromDateTime,
                'toDateTime' => $toDateTime,
                'file' => $req->file,
                'user_id' => $req->user_id
            ];
            $this->activity->createOrUpdate($data);
            return redirect()->back()->with('success', 'Menambahkan aktivitas berhasil');
        }
        catch(\Exception $e) {
            \Log::info($e->getMessage() . ' : ' . $e->getLine() . ' : ' . $e->getFile());
        }
    }

    public function drop($id) {
        try {
            $this->activity->drop($id);
            return redirect()->back();
        }catch(\Exception $e) {
            \Log::info($e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\ActivityCommittee;
use App\User;
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

        return view('pages.activity', compact('result'));
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
                'date.required' => 'Tanggal kegiatan harus diisi',
                'time.required' => 'Waktu kegiatan harus diisi',
                'user_id.required' => 'Panitia pelaksana kegiatan harus dipilih',
                // 'file.mimes' => 'Tipe file harus jpeg, jpg, png',
                // 'file.size' => 'Maksimum ukuran file : 2040kb',
            ];
            $validator = Validator::make($req->all(), [
                'name' => 'required',
                'location' => 'required',
                'date' => 'required',
                'time' => 'required',
                'user_id' => 'required',
                // 'file' => 'nullable|mimes:jpeg,png|max:2000',
            ], $messages);
            if ($validator->fails()) {
                return redirect('/tambah-kegiatan')
                            ->withErrors($validator)
                            ->withInput();
            }
            //change date format
            $explode = explode("-", $req->date);
            $explodeFrom = explode("/", $explode[0]);
            $explodeTo = explode("/", $explode[1]);
            $from = str_replace(" ", "", $explodeFrom[2] . '-' . $explodeFrom[0] . '-' . $explodeFrom[1]);
            $to = str_replace(" ","",$explodeTo[2] . '-' .$explodeTo[0]. '-'. $explodeTo[1]);
            
            $data = [
                'name' => $req->name,
                'location' => $req->location,
                'from' => $from,
                'to' => $to,
                'time' => date("H:i:s", strtotime($req->time)),
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

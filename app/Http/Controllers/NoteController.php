<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Note;
use App\User;
use Validator;

class NoteController extends Controller
{
    private $notes, $user;
    public function __construct() {
        $this->notes = new Note();
        $this->user = new User();
    }

    public function create(Request $req) {
        try {
            $messages = [
                'name.required' => 'Nama notes harus diisi',
                'dateFrom.required' => 'Tanggal mulai notes harus diisi',
                'timeFrom.required' => 'Waktu mulai notes harus diisi',
                'dateTo.required' => 'Tanggal berakhir notes harus diisi',
                'dateTo.after_or_equal:date' => 'Tanggal harus lebih besar dari tanggal mulai',
                'timeTo.required' => 'Waktu berakhir notes harus diisi',
            ];
            $validator = Validator::make($req->all(), [
                'name' => 'required',
                'dateFrom' => 'required',
                'timeFrom' => 'required',
                'dateTo' => 'required|after_or_equal:date:dateFrom',
                'timeTo' => 'required',
            ], $messages);
            if ($validator->fails()) {
                return redirect('/rincian-kegiatan')
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
                'fromDateTime' => $fromDateTime,
                'toDateTime' => $toDateTime,
            ];

            $this->notes->create($data);
            return redirect()->back()->with('success', 'Menambahkan notes berhasil');
        }catch(\Exception $e) {
            \Log::info($e->getMessage());
        }
    }

    public function drop($id) {
        try {
            $this->notes->drop($id);
            return redirect()->back();
        }catch(\Exception $e) {
            \Log::info($e->getMessage());
        }
    }
}

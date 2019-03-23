<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Note;
use App\User;
use Validator;
use Carbon\Carbon;

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
                'dateFrom.required' => 'Tanggal mulai harus diisi',
                'timeFrom.required' => 'Waktu mulai harus diisi',
                'dateTo.required' => 'Tanggal berakhir harus diisi',
                'timeTo.required' => 'Waktu berakhir notes harus diisi',
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
                'timeFrom' => 'required',
                'dateFrom' => ['required', 
                    function($attribute, $value, $fail) use ($fromDateTime) {
                        if(strtotime($fromDateTime) < strtotime(date("Y-m-d H:i:s")))
                            $fail("Tanggal dan jam harus lebih besar atau sama dengan hari ini");
                    }
                ],
                'dateTo' => ['required', 
                    function($attribute, $value, $fail) use ($fromDateTime, $toDateTime) {
                        if(strtotime($fromDateTime) > strtotime($toDateTime))
                            $fail("Tanggal dan jam selesai harus lebih besar atau sama dengan tanggal dan jam mulai");
                    }
                ],
                'timeTo' => 'required',
            ], $messages);
            if ($validator->fails()) {
                return redirect('/rincian-kegiatan')
                            ->withErrors($validator)
                            ->withInput();
            }
            //change date format
           
            
            $data = [
                'name' => $req->name,
                'fromDateTime' => $fromDateTime,
                'toDateTime' => $toDateTime,
                'location' => $req->location
            ];

            $this->notes->create($data);
            return redirect()->back()->with('success', 'Menambahkan notes berhasil');
        }catch(\Exception $e) {
            \Log::info($e->getMessage() . " : " . $e->getFile() . " : " . $e->getLine());
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;

class GuestController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    public function index () {
        $activity = new Activity();
        $result = $activity->getAllActivityCalendar();
        return view('pages.home', compact('result'));
    }

    public function loginIndex () {
        return view('pages.login');
    }
}

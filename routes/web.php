<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/masuk', function () {
    return view('pages.login');
});

Route::get('/rincian-kegiatan', function () {
    return view('pages.activity');
});

Route::get('/tambah-kegiatan', function () {
    return view('pages.manageActivity');
});

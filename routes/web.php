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

Route::get('/', 'GuestController@index')->name('home');

Route::get('/masuk', 'GuestController@loginIndex')->name('signIn');

Route::get('/rincian-kegiatan', 'ActivityController@activityView')->name('activity')->middleware('auth');
Route::get('/tambah-kegiatan', 'ActivityController@manageActivityView')->name('manageActivity')->middleware(['auth','admin']);
Route::post('/tambah-kegiatan', 'ActivityController@create')->name('createActivity')->middleware(['auth','admin']);

Auth::routes();


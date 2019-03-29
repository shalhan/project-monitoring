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

Route::get('/kelola-kegiatan', 'ActivityController@manageActivityView')->name('manageActivity')->middleware(['auth','admin']);
Route::get('/kelola-kegiatan/{id}/edit', 'ActivityController@updateActivityView')->name('updateActivityView')->middleware(['auth','admin']);
Route::put('/kelola-kegiatan/{id}/edit', 'ActivityController@updateOrCreate')->name('updateActivity')->middleware(['auth','admin']);
Route::post('/kelola-kegiatan/{id}', 'ActivityController@updateOrCreate')->name('createActivity')->middleware(['auth','admin']);
Route::delete('/kelola-kegiatan/{id}', 'ActivityController@drop')->name('deleteActivity')->middleware(['auth','admin']);


Route::get('/kelola-catatan', 'NoteController@noteView')->name('viewNote')->middleware(['auth', 'lecture']);
Route::get('/kelola-catatan/{id}/edit', 'NoteController@updateNoteView')->name('updateNoteView')->middleware(['auth','lecture']);
Route::put('/kelola-catatan/{id}/edit', 'NoteController@updateOrCreate')->name('updateNote')->middleware(['auth','lecture']);
Route::post('/kelola-catatan/{id}', 'NoteController@updateOrCreate')->name('createNote')->middleware(['auth', 'lecture']);
Route::delete('/kelola-catatan/{id}', 'NoteController@drop')->name('deleteNote')->middleware(['auth', 'lecture']);

Auth::routes();


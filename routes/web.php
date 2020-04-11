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
    return view('welcome');
})->name('guest-page');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/home', 'HomeController@store')->name('home.store');
Route::get('/announcements/{announcement_id}/edit', 'HomeController@edit')->name('announcement.edit');
Route::patch('/announcements/{announcement_id}', 'HomeController@update')->name('announcement.update');
Route::delete('/announcements/{announcement_id}', 'HomeController@destroy')->name('home.destroy');

Route::get('/home/action', 'HomeController@action')->name('home.action');

Route::get('/about', 'AboutController@index')->name('about');

Route::get('/faq', 'FAQController@index')->name('faq');
Route::post('/faq', 'FAQController@storeQuestion')->name('faq.store.question');
Route::patch('/faq', 'FAQController@storeAnswer')->name('faq.store.answer');
Route::delete('/faq/{faq_id}', 'FAQController@destroyById')->name('faq.destroy');
Route::delete('/faq/{question}', 'FAQController@destroyByQ')->name('q.destroy');

Route::get('/announcements/edit/{announcement_id}', 'EditAnnouncement@index')->name('editannouncement');
//Route::post('/home', 'HomeController@delete')->name('home');

//Route::post('/home','CreateCourseController@insert');

Route::get('/kursai','CourseController@index')->name('Kursai');

Route::get('/paskaitos','EventController@index')->name('RouteToEvents');

Route::get('eventai','CreateEventController@index');
Route::post('eventai/fetch', 'CreateEventController@fetch')->name('eventcontroller.fetch');

Route::get('findSteamCenter/{id}','CreateEventController@findSteamCenter');

Route::get('/time','TimeController@index');

Route::group(['prefix' => 'sukurti-kursa', 'middleware' => ['auth' => 'admin']], function(){
    Route::get('/', 'CreateCourseController@index')->name('RouteToCreateCourse');
    Route::post('/','CreateCourseController@insert');
});

Route::group(['prefix' => 'eventai'], function(){
    Route::get('/', 'CreateEventController@index')->name('RouteToCreateEvent');
    Route::post('/','CreateEventController@insert');
});

Route::group(['prefix' => 'vartotoju-valdymas', 'middleware' => ['auth' => 'admin']], function(){
    Route::get('/', 'UserController@index')->name('RouteToUserManagement');
});


Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
});


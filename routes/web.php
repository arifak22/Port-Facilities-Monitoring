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
use App\Helpers\Pel;
Route::get('/',function(){
	return redirect('/home');
});
Route::middleware(['auth', 'access'])->group(function () {
	Pel::routeController('/home','Faspel\HomeController');
	Pel::routeController('/inspeksi','Faspel\InspeksiController');
	Pel::routeController('/berita-acara','Faspel\BaController');
	Pel::routeController('/master','Faspel\MasterController');
});

Route::post('login', [ 'as' => 'login', 'uses' => 'LoginController@getIndex']);
Pel::routeController('/login','Auth\LoginController');
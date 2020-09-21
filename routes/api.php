<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Helpers\Pel;
Pel::routeController('/auth','Api\AuthController');

Route::middleware(['jwt.verify'])->group(function () {
    Pel::routeController('/master','Faspel\Api\MasterController');
    Pel::routeController('/inspeksi','Faspel\Api\InspeksiController');
    Pel::routeController('/berita-acara','Faspel\Api\BaController');
});

<?php

use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    Route::get('/menu', 'MenuController@index');

    Route::get('sarana/getList', 'SaranaController@getList');

    Route::resource('dipo', 'DipoController')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('bogie', 'BogieController')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('stasiun', 'StasiunController')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('jalur', 'JalurController')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('jenisDetailPekerjaan', 'JenisDetailPekerjaanController')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('jenisPekerjaan', 'JenisPekerjaanController')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('jenisSarana', 'JenisSaranaController')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('order', 'OrderController')->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::resource('sarana', 'SaranaController')->only(['index', 'store', 'update', 'destroy']);
    Route::resource('user', 'UserController')->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::resource('programKerja', 'ProgramKerjaController')->only(['index', 'store']);

    Route::resource('orderProgress', 'OrderProgressController')->only(['store']);
    Route::resource('orderDetail', 'OrderDetailController')->only(['update', 'destroy']);
});

Route::get('report/bulanan', 'ReportController@bulanan');
Route::get('report/tahunan', 'ReportController@tahunan');

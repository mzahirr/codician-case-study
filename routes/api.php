<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => ['api']], function () {

    //companies
    Route::get(    '/companies', [\App\Http\Controllers\Company\CompanyController::class, 'all']);
    Route::get(    '/companies/{id}', [\App\Http\Controllers\Company\CompanyController::class, 'detail']);
    Route::post(   '/companies', [\App\Http\Controllers\Company\CompanyController::class, 'create']);
    Route::delete(   '/companies/{id}', [\App\Http\Controllers\Company\CompanyController::class, 'delete']);

    //person
    Route::get(    '/companies/{companyId}/persons', [\App\Http\Controllers\Person\PersonController::class, 'all']);
    Route::post(   '/companies/{companyId}/persons', [\App\Http\Controllers\Person\PersonController::class, 'create']);
    Route::delete(   '/persons/{id}', [\App\Http\Controllers\Person\PersonController::class, 'delete']);

    //address
    Route::get(    '/companies/{companyId}/address', [\App\Http\Controllers\Address\AddressController::class, 'all']);
    Route::post(   '/companies/{companyId}/address', [\App\Http\Controllers\Address\AddressController::class, 'create']);
    Route::delete(   '/address/{id}', [\App\Http\Controllers\Address\AddressController::class, 'delete']);

});


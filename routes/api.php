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

Route::group(['prefix' => 'v1'], function () {
    Route::post('account', [
        'uses' => 'AccountController@create_account',
        'as' => 'Create Account'
    ]);

});


Route::group(['prefix' => 'v1'], function () {
    Route::post('transaction/credit', [
        'uses' => 'TransactionController@process_credit_transaction',
        'as' => 'Credit Account'
    ]);

    Route::post('transaction/debit', [
        'uses' => 'TransactionController@process_debit_transaction',
        'as' => 'Debit Account'
    ]);

});

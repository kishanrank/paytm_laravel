<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/initiate','PaytmController@initiate')->name('initiate.payment');
Route::post('/payment','PaytmController@pay')->name('make.payment');
Route::post('/payment/status', 'PaytmController@paymentCallback')->name('status');

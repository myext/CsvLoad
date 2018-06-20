<?php
/**

 */

Route::group([
    'prefix' => 'zvg',
    'middleware' => \Illuminate\Session\Middleware\StartSession::class,
    'namespace' => 'Zvg\CsvLoad\Http\Controllers'],

    function () {

        Route::post('/csvload', ['as' => 'zvg.csvload', 'uses' => 'CsvLoadController@load']);

        Route::get('/csvinsert', ['as' => 'zvg.dbinsert', 'uses' => 'CsvLoadController@insert']);

    });
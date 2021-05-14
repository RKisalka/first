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


Route::group(['middleware' => ['api']], function () {

    Route::group(['prefix' => 'v1'], function () {

        Route::group(['prefix' => 'document'], function () {


            Route::get('/student/list', 'StudentController@list')->name('student.list');
            Route::get('/student/show/{id}', 'StudentController@show')->name('student.show');
            Route::post('/student/create', 'StudentController@create')->name('student.create');
            Route::put('/student/update/{id}', 'StudentController@update')->name('student.update');
            Route::delete('/student/delete/{id}', 'StudentController@delete')->name('student.delete');


            Route::get('/subject/list', 'SubjectController@list')->name('subject.list');
            Route::post('/subject/create', 'SubjectController@create')->name('subject.create');

        });

    });
});

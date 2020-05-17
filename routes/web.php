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
    return redirect('login');
});

Auth::routes();

Route::group(['prefix' => 'home',  'middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::post('/', 'HomeController@actions');

    Route::prefix('management')->group(function () {
        Route::prefix('sectors')->group(function () {
            Route::get('/', 'Management\SectorsController@index')->name('sectors');
            Route::post('/', 'Management\SectorsController@actions');
        });

        Route::prefix('users')->group(function () {
            Route::get('/', 'Management\UsersController@index')->name('users');
            Route::post('/', 'Management\UsersController@actions');
        });
    });

    Route::prefix('research')->group(function () {
        Route::get('/{research_id}', 'Researches\ProgressController@index')->name('research_progress');
        Route::post('/', 'Researches\ProgressController@actions')->name('research');
        Route::get('/{research_id}/result', 'Researches\ProgressController@result')->name('research_result');
        Route::get('/{research_id}/pdf', 'Researches\ProgressController@pdf')->name('research_pdf');
    });

    // Route::prefix('reports')->group(function () {
    //     Route::prefix('services')->group(function () {
    //         Route::get('/', 'Reports\ServicesController@index')->name('reports_services');
    //         Route::post('/', 'Reports\ServicesController@actions');
    //     });
    // });
});

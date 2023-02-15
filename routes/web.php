<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EventsController as AdminEventsController;

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
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function() {

    Route::get('/', [AdminController::class, 'home']);

    Route::controller(AdminEventsController::class)->group(function () {
        Route::get('/eventos', 'index');
        Route::get('/eventos/create', 'create');
        Route::get('/eventos/{id}/edit', 'edit');
        Route::get('/eventos/{id}/copy', 'copy');
        Route::post('/eventos/store', 'store');
        Route::post('/eventos/update', 'update');
        Route::post('/eventos/duplicate', 'duplicate');
    });

});

require __DIR__.'/auth.php';

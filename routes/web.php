<?php

use Illuminate\Support\Facades\Route;

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

Route::GET('/', function () {
    return view('welcome');
})->name('index');

Route::middleware(['auth:sanctum', 'verified'])->prefix('dashboard')->group(function(){

    Route::GET('/', [\App\Http\Controllers\AdminPanelController::class, 'dashboard'])->name('dashboard');
    Route::GET('/members', [\App\Http\Controllers\AdminPanelController::class, 'dashboard'])->name('members');


    Route::GET('/view_roles', [\App\Http\Controllers\SuperAdminController::class, 'view_roles'])->name('view_roles');
    Route::POST('/update_role', [\App\Http\Controllers\SuperAdminController::class, 'update_role'])->name('update_role');

});




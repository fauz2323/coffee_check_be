<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes([
    'register' => false,
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//admin
//user
Route::get('/admin/user', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('user.index');
Route::get('/admin/user/detail/{id}', [App\Http\Controllers\Admin\UserController::class, 'detail'])->name('user.detail');
Route::get('/admin/user/delete/{id}', [App\Http\Controllers\Admin\UserController::class, 'delete'])->name('user.delete');
Route::post('/admin/user/changepass/{id}', [App\Http\Controllers\Admin\UserController::class, 'changePassword'])->name('user.changePassword');

//history
Route::get('/admin/history', [App\Http\Controllers\Admin\HistoryAdminController::class, 'index'])->name('history.index');

//hexcolor
Route::get('/admin/hexcolor', [App\Http\Controllers\Admin\HexColorController::class, 'index'])->name('hexcolor.index');
Route::post('/admin/hexcolor/store', [App\Http\Controllers\Admin\HexColorController::class, 'store'])->name('hexcolor.store');
Route::post('/admin/hexcolor/edit/{id}', [App\Http\Controllers\Admin\HexColorController::class, 'storeEdit'])->name('hexcolor.edit');
Route::get('/admin/hexcolor/delete/{id}', [App\Http\Controllers\Admin\HexColorController::class, 'delete'])->name('hexcolor.delete');
Route::get('/admin/hexcolor/view/{id}', [App\Http\Controllers\Admin\HexColorController::class, 'editView'])->name('hexcolor.view');


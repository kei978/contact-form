<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', [ContactController::class, 'index'])->name('index');
Route::post('/confirm', [ContactController::class, 'confirm']);
Route::post('/thanks', [ContactController::class, 'store']);
Route::get('/admin', [ContactController::class, 'admin'])->middleware('auth');
Route::get('/admin/detail/{id}', [ContactController::class, 'detail'])->middleware('auth')->name('admin.detail');
Route::delete('/admin/delete/{id}', [ContactController::class, 'destroy'])->name('admin.delete');
Route::get('/register', [ContactController::class, 'showRegisterForm'])->name('register.index');
Route::post('/register', [ContactController::class, 'registerStore'])->name('register.store');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
Route::get('/admin/export', [ContactController::class, 'export'])->name('admin.export');

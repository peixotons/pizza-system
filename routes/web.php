<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ResetPasswordController;
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

//Autenticação
Route::get('/', [AuthController::class, 'indexLogin'])->name('login.page');
Route::post('/auth', [AuthController::class, 'auth'])->name('auth.user');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Registro
Route::get('/register', [AuthController::class, 'indexRegister'])->name('register.page');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
//Esqueceu a senha
Route::get('/forgot-password', [PasswordController::class, 'index'])->name('password.request');
Route::post('/forgot-password', [PasswordController::class, 'sendPasswordEmail'])->name('password.email');
//Troca a senha
Route::get('/reset-password', [ResetPasswordController::class, 'index'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.page')->middleware('auth');

Route::post('/debt/store', [DebtController::class, 'store'])->name('debt.store');
Route::post('/debt/{id}/audit', [DebtController::class, 'audit'])->name('debt.audit')->middleware('auth');
Route::post('/debt/{id}', [DebtController::class, 'update'])->name('debt.update');



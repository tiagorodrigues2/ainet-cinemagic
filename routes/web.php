<?php

use App\Http\Controllers\CostumersController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmployeesController;

Route::get('/', [Controller::class, 'index'])->name('home');
Route::get('/login', [LoginController::class,'index'])->name('login');
Route::post('/login', [LoginController::class,'login'])->name('login.submit');
Route::get('/register', [LoginController::class,'register'])->name('register');
Route::post('/register', [LoginController::class,'registerUser'])->name('register.submit');
Route::post('/logout', [LoginController::class,'logout'])->name('logout');
Route::get('/costumers', [CostumersController::class, 'index'])->name('costumers');
Route::delete('/costumers/{id}', [CostumersController::class, 'delete'])->name('costumers.delete');
Route::post('/costumers/toggle-block', [CostumersController::class, 'toggleBlock'])->name('costumers.toggle-block');
Route::get('/employees', [EmployeesController::class, 'index'])->name('employees');


<?php

use App\Http\Controllers\CostumersController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MoviesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\PurchasesController;

Route::get('/', [Controller::class, 'index'])->name('home');
Route::get('/movie/{id}', [MoviesController::class, 'movie'])->name('movie');
Route::get('/movies', [MoviesController::class, 'movies'])->name('movies');

Route::get('/login', [LoginController::class,'index'])->name('login');
Route::post('/login', [LoginController::class,'login'])->name('login.submit');
Route::get('/register', [LoginController::class,'register'])->name('register');
Route::post('/register', [LoginController::class,'registerUser'])->name('register.submit');
Route::post('/logout', [LoginController::class,'logout'])->name('logout');
Route::get('/profile', [LoginController::class, 'profile'])->name('profile');
Route::patch('/profile/photo', [LoginController::class, 'savePhoto'])->name('profile.photo.update');
Route::put('/profile/password', [LoginController::class, 'savePassword'])->name('profile.password.update');

Route::get('/costumers', [CostumersController::class, 'index'])->name('costumers');
Route::delete('/costumers/{id}', [CostumersController::class, 'delete'])->name('costumers.delete');
Route::post('/costumers/toggle-block', [CostumersController::class, 'toggleBlock'])->name('costumers.toggle-block');

Route::get('/employees', [EmployeesController::class, 'index'])->name('employees');
Route::get('/employees/create', [EmployeesController::class, 'create'])->name('employees.register');
Route::post('/employees/create', [EmployeesController::class, 'register'])->name('employees.register.submit');
Route::get('/employees/{id}', [EmployeesController::class, 'show'])->name('employees.show');
Route::post('/employees/save', [EmployeesController::class, 'save'])->name('employees.save');

Route::get('/purchases', [PurchasesController::class, 'index'])->name('purchases');

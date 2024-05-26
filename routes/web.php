<?php

use App\Http\Controllers\CustomersController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\ScreeningController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\PurchasesController;
use App\Http\Controllers\CartController;

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

Route::get('/customers', [CustomersController::class, 'index'])->name('customers');
Route::delete('/customers/{id}', [CustomersController::class, 'delete'])->name('customers.delete');
Route::post('/customers/toggle-block', [CustomersController::class, 'toggleBlock'])->name('customers.toggle-block');

Route::get('/employees', [EmployeesController::class, 'index'])->name('employees');
Route::get('/employees/create', [EmployeesController::class, 'create'])->name('employees.register');
Route::post('/employees/create', [EmployeesController::class, 'register'])->name('employees.register.submit');
Route::get('/employees/{id}', [EmployeesController::class, 'show'])->name('employees.show');
Route::post('/employees/save', [EmployeesController::class, 'save'])->name('employees.save');

Route::get('/purchases', [PurchasesController::class, 'index'])->name('purchases');

Route::get('/screening/{id}', [ScreeningController::class, 'screening'])->name('screening');

Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/cart/checkout', [CartController::class, 'pay'])->name('cart.checkout.submit');
Route::patch('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
Route::delete('/cart/remove/{seat_id}', [CartController::class, 'remove'])->name('cart.remove');

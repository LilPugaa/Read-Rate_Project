<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\RatingController;
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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [BookController::class, 'index'])->name('index');

Route::get('/authors/top', [AuthorController::class, 'index'])->name('authors.top');

Route::get('/ratings/create', [RatingController::class, 'create'])->name('ratings.create');

Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');

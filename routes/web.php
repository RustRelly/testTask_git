<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
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

Route::get('/books', [BookController::class, 'index']);
Route::post('/books/create', [BookController::class, 'store']);
Route::patch('/books/check_out', [BookController::class, 'checkOut']);
Route::delete('/books/{book}', [BookController::class, 'destroy']);

Route::get('/authors', [AuthorController::class, 'index']);


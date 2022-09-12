<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('store-book/{id?}', [BookController::class, 'storeBook']);
    Route::post('change-book/{bookId}', [BookController::class, 'changeBook']);
    Route::post('change-book-jacket/{bookId}', [BookController::class, 'changeBookJacket']);
    Route::delete('delete-book/{bookId}', [BookController::class, 'deleteBook']);
    Route::get('book/{bookId}', [BookController::class, 'showBook']);
    Route::get('books', [BookController::class, 'getBooks']);
});

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
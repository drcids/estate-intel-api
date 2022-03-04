<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\BookController;

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

Route::get('/external-books', [BookController::class, 'getExternalBooks']); 
Route::post('/v1/books', [BookController::class, 'create']); 
Route::get('/v1/books', [BookController::class, 'read']); 
Route::patch('/v1/books/{id}', [BookController::class, 'update'])->where('id', '[0-9]+'); 
Route::delete('/v1/books/{id}', [BookController::class, 'delete'])->where('id', '[0-9]+'); 
Route::get('/v1/books/{id}', [BookController::class, 'show'])->where('id', '[0-9]+'); 
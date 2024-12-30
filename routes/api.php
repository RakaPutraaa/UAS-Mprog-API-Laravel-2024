<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KabupatenController;
use App\Http\Controllers\ListVillaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/register', [UserController::class,'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/add-villa', [ListVillaController::class, 'store']);
});
Route::get('/show-detail-villa/{id}', [ListVillaController::class,'showDetail']);
Route::get('/show-all-villa',[ListVillaController::class,'index']);
// testing
Route::get('/kabupaten', [KabupatenController::class, 'index']);

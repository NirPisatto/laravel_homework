<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;

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

Route::prefix('posts')->group(function () {
    Route::get('', [PostController::class, 'index']);

    Route::get('{id}', [PostController::class, 'show']);

    Route::post('', [PostController::class, 'store']);

    Route::post('{id}', [PostController::class, 'update']);

    Route::delete('{id}', [PostController::class, 'destroy']);
});

Route::prefix('categories')->group(function () {
    Route::get('', [CategoryController::class, 'index']);
});

Route::get('', function (Request $request) {
    return redirect('/doc');
});

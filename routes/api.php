<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KanbanController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::resource('task', KanbanController::class)->except(['edit', 'create']);
    Route::resource('project', ProjectController::class)->except(['edit', 'create']);
    Route::resource('taskstatus', TaskStatusController::class)->except(['edit', 'create']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/profile', [ProfileController::class, 'profile']);

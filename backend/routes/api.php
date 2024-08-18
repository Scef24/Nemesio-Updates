<?php

use App\Http\Controllers\AnnouncementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManager;
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
Route::post('/login', [AuthManager::class, 'login']);
Route::post('/logout', [AuthManager::class, 'logout']); 
Route::post('/register',[AuthManager::class, 'register']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('/announcement',AnnouncementController::class);
Route::post('/sortAnnouncement',[AnnouncementController::class,'sortAnnouncement']);
Route::post('/searchAnnouncement',[AnnouncementController::class,'searchAnnouncement']);
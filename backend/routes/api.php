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
Route::post('/login', [AuthManager::class, 'login'])->name('login'); 
Route::get('/announcements',[AnnouncementController::class,'announcements']);
Route::post('/register',[AuthManager::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthManager::class, 'logout']); 
    Route::apiResource('/announcement',AnnouncementController::class);
    Route::post('/change-password', [AuthManager::class,'changePassword']);
    Route::post('/sortAnnouncement',[AnnouncementController::class,'sortAnnouncement']);
    Route::get('/history',[AnnouncementController::class,'getHistory']);
    Route::get('/announcements/filter', [AnnouncementController::class, 'filterByStatus']);
});

Route::post('/searchAnnouncement',[AnnouncementController::class,'searchAnnouncement']);
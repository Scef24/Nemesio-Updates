<?php

use App\Http\Controllers\AnnouncementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManager;
use App\Http\Controllers\AdminController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthManager::class, 'logout']); 
    Route::apiResource('/announcement',AnnouncementController::class);
    Route::post('/change-password', [AuthManager::class,'changePassword']);
    Route::post('/sortAnnouncement',[AnnouncementController::class,'sortAnnouncement']);
    Route::get('/history',[AnnouncementController::class,'getHistory']);
    Route::get('/announcements/filter', [AnnouncementController::class, 'filterByStatus']);
});

Route::post('/searchAnnouncement',[AnnouncementController::class,'searchAnnouncement']);

Route::middleware(['auth:sanctum', 'super_admin'])->group(function () {
    Route::get('/users', [AdminController::class, 'getAllUsers']);
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
    Route::post('/users/{id}/block', [AdminController::class, 'blockUser']);
    Route::get('/announcements',[AnnouncementController::class,'announcements']);
    Route::post('/register',[AuthManager::class, 'register']);
    Route::post('/users/{id}/findBlock', [AuthManager::class, 'findBlockUser']);
    Route::post('/users/{id}/unblock', [AdminController::class, 'unblockUser']);
    
});
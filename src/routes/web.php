<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceListController;
use App\Http\Controllers\AttendanceDetailController;
use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\RequestListController;
use App\Http\Controllers\Admin\AdminCorrectionController;
use App\Http\Controllers\Admin\AdminCsvController;





Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get(
    '/stamp_correction_request/list',
    [RequestListController::class, 'index']
)->middleware('auth');


// 勤怠ルート追加
Route::middleware(['auth'])->group(function () {

    Route::get('/attendance', [AttendanceController::class, 'index']);

    Route::post('/attendance/clockin', [AttendanceController::class, 'clockIn']);

    Route::post('/attendance/clockout', [AttendanceController::class, 'clockOut']);

    Route::get('/attendance/list', [AttendanceListController::class, 'index'])->middleware('auth');

    Route::post('/attendance/break/start', [AttendanceController::class, 'breakStart']);

    Route::post('/attendance/break/end', [AttendanceController::class, 'breakEnd']);

    Route::get('/attendance/detail/{id}', [AttendanceDetailController::class, 'show'])->middleware('auth');

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth'])->group(
    function () {

        Route::get('/attendance/list', [AdminAttendanceController::class, 'index']);

        Route::get('/staff/list', [AdminStaffController::class, 'index']);
    }
);

Route::prefix('admin')->middleware(['auth'])->group(function () {

    Route::get(
        '/stamp_correction_request/list',
        [AdminCorrectionController::class, 'index']
    );

    Route::post(
        '/stamp_correction_request/approve/{id}',
        [AdminCorrectionController::class, 'approve']
    );
});

Route::get(
    '/admin/attendance/csv/{user_id}',
    [AdminCsvController::class, 'export']
)->middleware('auth');

        require __DIR__ . '/auth.php';

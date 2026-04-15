<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceListController;
use App\Http\Controllers\AttendanceDetailController;
use App\Http\Controllers\RequestListController;

use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\AdminCorrectionController;
use App\Http\Controllers\Admin\AdminCsvController;

use App\Http\Controllers\Admin\RequestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/hello', function () {
    return 'Hello';
});

Route::get('/admin/login', function () {
    return view('admin.login');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| 一般ユーザー
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // 打刻画面
    Route::get('/attendance', [AttendanceController::class, 'index']);

    // 出勤
    Route::post('/attendance/clockin', [AttendanceController::class, 'clockIn']);

    // 退勤
    Route::post('/attendance/clockout', [AttendanceController::class, 'clockOut']);

    // 休憩
    Route::post('/attendance/break/start', [AttendanceController::class, 'breakStart']);

    Route::post('/attendance/break/end', [AttendanceController::class, 'breakEnd']);

    // 勤怠一覧
    Route::get('/attendance/list', [AttendanceListController::class, 'index']);

    // 勤怠詳細
    Route::get('/attendance/detail', [AttendanceDetailController::class, 'show']);


    Route::post('/attendance/detail/{id}', [AttendanceDetailController::class, 'update']);

    // 修正申請一覧
    Route::get('/stamp_correction_request/list', [RequestListController::class, 'index']);

    Route::post('/attendance/break-return', [AttendanceController::class, 'breakReturn']);

    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'show']);
});


/*
|--------------------------------------------------------------------------
| 管理者
|------------------------------------------
--------------------------------
*/

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {

    // 管理者 勤怠一覧
    Route::get('/attendance/list', [AdminAttendanceController::class, 'index']);

    // スタッフ一覧
    Route::get('/staff/list', [AdminStaffController::class, 'index']);

    // 修正申請一覧
    Route::get('/stamp_correction_request/list', [AdminCorrectionController::class, 'index']);

    // 申請詳細
    Route::get('/stamp_correction_request/detail/{id}', [AdminCorrectionController::class, 'show']);

    // 修正申請承認
    Route::post('/stamp_correction_request/approve/{id}', [AdminCorrectionController::class, 'approve']);

    // CSV出力
    Route::get('/attendance/csv/{user_id}', [AdminCsvController::class, 'export']);

    // スタッフ別勤怠
    Route::get('/attendance/staff/{id}', [AdminAttendanceController::class, 'staff']);

    Route::get('/attendance/{id}', [AdminAttendanceController::class, 'show']);

    // 申請詳細
    Route::get('/stamp_correction_request/detail/{id}', [AdminCorrectionController::class, 'show']);

    // 承認
    Route::post('/stamp_correction_request/approve/{id}', [AdminCorrectionController::class, 'approve']);

    Route::post('/request/approve/{id}', [RequestController::class, 'approve']);

    Route::post('/admin/attendance/{id}', [AdminAttendanceController::class, 'update']);

    Route::get('/admin/attendance', [AdminAttendanceController::class, 'show']);

    Route::get('/admin/attendance/{id}', [AdminAttendanceController::class, 'show']);
});

/*
|--------------------------------------------------------------------------
| プロフィール
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';

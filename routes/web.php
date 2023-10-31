<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\Login\LoginController;
use App\Http\Controllers\Admin\Profile\ProfileController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\Branch\BranchController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
Route::get('/', function () {
    return redirect()->route('Admin.Dashboard');
});

Route::prefix('admin')->group(function () {

    Route::get('/login', [LoginController::class, 'Login'])->name('Admin.Login');
    Route::post('/login',[LoginController::class,'Check'])->name('Admin.Login.Check');
    Route::get('/logout',[LoginController::class,'Logout'])->name('Admin.Login.Logout');
    Route::get('/user/profile', [ProfileController::class, 'Profile'])->name('Admin.User.Profile');
    Route::post('/user/profile/store', [ProfileController::class, 'Store'])->name('Admin.User.Profile.Store');
    Route::get('/expire', [HomeController::class, 'Expire'])->name('Admin.Expire');

    Route::middleware(['AdminLoginMiddleware'])->group(function () {
        Route::get('/', [HomeController::class, 'Dashboard'])->name('Admin.Dashboard');
        Route::get('/student/download', [HomeController::class, 'StudentDownload'])->name('Admin.Student.Download');
        Route::get('/student/new/import', [HomeController::class, 'ImportNewStudent'])->name('Admin.Student.New.Import');
        Route::post('/student/details', [HomeController::class, 'StudentDetails'])->name('student.details');

        Route::get('/batches', [BatchController::class, 'Batches'])->name('Admin.Batch');
        Route::get('/courses', [CourseController::class, 'Courses'])->name('Admin.Course');
        Route::get('/students', [StudentController::class, 'Students'])->name('Admin.Student');
        Route::post('/course/batch/list', [CourseController::class, 'CourseBatch'])->name('course.batch');


        Route::get('/attendance', [AttendanceController::class, 'Attendance'])->name('Admin.Attendance');
        Route::get('/attendance/import', [AttendanceController::class, 'Import'])->name('Admin.Attendance.Import');
        Route::post('/attendance/import/store', [AttendanceController::class, 'ImportStore'])->name('Admin.Attendance.Import.Store');
        Route::post('/attendance/store', [AttendanceController::class, 'AttendanceStore'])->name('Admin.Attendance.Store');
        Route::post('/attendance/single/store', [AttendanceController::class, 'AttendanceSingleStore'])->name('Admin.Attendance.Single.Store');
        Route::get('/attendance/direct', [AttendanceController::class, 'Direct'])->name('Admin.Attendance.Direct');
        Route::post('/attendance/direct/store', [AttendanceController::class, 'DirectStore'])->name('Admin.Attendance.Direct.Store');


    });
});

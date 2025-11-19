<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\SalaryController;

Route::get('/', [LoginController::class, 'login'])->name('auth.login');
Route::post('/auth/stores', [LoginController::class, 'stores'])->name('auth.stores');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'register'])->name('auth.register');
Route::post('/auth/store', [RegisterController::class, 'store'])->name('auth.store');

Route::middleware('authCheck')->group(function () {
    Route::middleware(['admin'])->group(function () {
        Route::get('/auth/homepage', [LoginController::class, 'homepage'])->name('auth.homepage');
    });

    Route::middleware(['employee'])->group(function () {
        Route::get('/employee/homepage', [RegisterController::class, 'homepage'])->name('employee.homepage');
    });

    // Employees
    Route::get('/index', [EmployeeController::class, 'index'])->name('emp.index');
    Route::post('/store', [EmployeeController::class, 'store'])->name('emp.store');
    Route::get('/emp/show/{id}', [EmployeeController::class, 'show'])->name('emp.show');
    Route::get('/emp/edit/{id}', [EmployeeController::class, 'edit'])->name('emp.edit');
    Route::post('/emp/update/{id}', [EmployeeController::class, 'update'])->name('emp.update');
    Route::delete('/emp/{id}', [EmployeeController::class, 'destroy'])->name('emp.destroy');
    Route::get('/export', [EmployeeController::class, 'export']);


    // Attendance
    Route::get('/att/index', [AttendanceController::class, 'index'])->name('att.index');
    Route::get('/att/create', [AttendanceController::class, 'create'])->name('att.create');
    Route::post('/att/store', [AttendanceController::class, 'store'])->name('att.store');
    Route::get('/att/show/{id}', [AttendanceController::class, 'show'])->name('att.show');
    Route::get('/att/edit/{id}', [AttendanceController::class, 'edit'])->name('att.edit');
    Route::post('/att/update/{id}', [AttendanceController::class, 'update'])->name('att.update');
    Route::delete('/att/{id}', [AttendanceController::class, 'destroy'])->name('att.destroy');
    Route::post('/att/checkIn', [AttendanceController::class, 'checkIn'])->name('att.checkIn');
    Route::post('/att/checkOut', [AttendanceController::class, 'checkOut'])->name('att.checkOut');

    // Profile
    Route::get('/profile/show/{id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit/{id}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');

    // Leave
    Route::get('/leave/index', [LeaveController::class, 'index'])->name('leave.index');
    Route::post('/leave/store', [LeaveController::class, 'store'])->name('leave.store');
    Route::post('/leave/{id}/approve', [LeaveController::class, 'approve'])->name('leave.approve');
    Route::post('/leave/{id}/reject', [LeaveController::class, 'reject'])->name('leave.reject');
    Route::delete('/leave/{id}', [LeaveController::class, 'destroy'])->name('leave.destroy');

    Route::prefix('salary')->group(function () {
    Route::get('/index', [SalaryController::class, 'index'])->name('salary.index');
    Route::post('/generate', [SalaryController::class, 'generateSalary'])->name('salary.generateSalary');
    Route::post('/store', [SalaryController::class, 'store'])->name('salary.store');
    Route::get('/show/{id}', [SalaryController::class, 'show'])->name('salary.show');
    Route::get('/edit/{id}', [SalaryController::class, 'edit'])->name('salary.edit');
    Route::post('/update/{id}', [SalaryController::class, 'update'])->name('salary.update');
    Route::delete('/delete/{id}', [SalaryController::class, 'destroy'])->name('salary.destroy');
    });
    Route::get('/export', [SalaryController::class, 'export'])->name('salary.export');

});
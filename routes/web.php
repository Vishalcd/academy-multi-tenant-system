<?php

use App\Exports\EmployeesExport;
use App\Exports\ExpensesExport;
use App\Exports\SportsExport;
use App\Exports\StudentsExport;

use App\Http\Controllers\AcademyController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

### Student Profile ###
Route::middleware(['auth', 'role:student', 'set.academy'])->group(function () {
    // Student Profile
    Route::get('/students/me', [StudentController::class, 'showMe'])->name('students.showMe');
    Route::get('/students/attendance', [StudentController::class, 'studentAttendance'])->name('students.showAttaendance');
});

### Employee Profile ###
Route::middleware(['auth', 'role:employee', 'set.academy'])->group(function () {
    // Employee Profile
    Route::get('/employees/me', [EmployeeController::class, 'showMe'])->name('employees.showMe');
});

### Users Routes ###
Route::middleware(['auth'])->group(function () {
    Route::put('/users/{id}', [UserController::class, 'update'])->name('user.update');
    Route::put('/users/{id}/update-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');
});

### App Routes ###
Route::middleware(['auth', 'role:manager,admin', 'set.academy'])->group(function () {
    // Home Route
    Route::get("/", [HomeController::class, "index"])->name('home');

    // Profile Route
    Route::get("/settings", [SettingController::class, "index"])->name('settings.index');
    Route::put("/settings", [SettingController::class, "update"])->name('settings.update');

    // Student Routes
    Route::get('students/export', function () {
        return Excel::download(new StudentsExport, 'students.xlsx');
    })->name('students.export');
    Route::resource("students", StudentController::class)->only([
        'index', 'store', 'show', 'update', 'destroy'
    ]);
    Route::post("/students/{id}/deposit-fees", [StudentController::class, 'depositFee']);


    // Employee Routes
    Route::get('/employees/export', function () {
        return Excel::download(new EmployeesExport, 'employees.xlsx');
    })->name('employees.export');
    Route::resource("employees", EmployeeController::class)->only([
        'index', 'store', 'show', 'update', 'destroy'
    ]);
    Route::post("/employees/{id}/deposit-salary", [EmployeeController::class, 'depositSalary']);

    // Expense Routes
    Route::get('expenses/export', function () {
        return Excel::download(new ExpensesExport, 'expenses.xlsx');
    })->name('expenses.export');
    Route::resource("expenses", ExpenseController::class)->only([
        'index', 'store', 'show', 'update', 'destroy'
    ]);
    Route::get('/download-recipt/{id}', [ExpenseController::class, 'downloadRecipt'])->name('expenses.download');

    // Sports Routes
    Route::get('sports/export', function () {
        return Excel::download(new SportsExport, 'sports.xlsx');
    })->name('sports.export');
    Route::resource("sports", SportController::class)->only([
        'index', 'store', 'show', 'update', 'destroy'
    ]);
});

### Admin Route ###
Route::middleware(['auth', 'role:admin', 'set.academy'])->group(function () {
    // Academies Routes
    Route::resource("academies", AcademyController::class)->only([
        'index', 'store', 'show', 'update', 'destroy'
    ]);
    Route::post("/academies/{id}/managers", [AcademyController::class, 'storeManager'])->name('academies.storeManager');

});

### Auth Routes ###
Route::middleware('guest')->group(function () {
    Route::get('/auth/login', [AuthController::class, 'index'])->name('login');
    Route::post('/auth/login', [AuthController::class, 'authenticate']);

    Route::get('/auth/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/auth/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/auth/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/auth/reset-password', [AuthController::class, 'reset'])->name('password.update');
});
//  Logout Route    
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// // Attendances Routes
Route::middleware(['auth', 'role:manager,student,employee'])->group(function () {
    Route::resource('attendances', AttendanceController::class)->only(['index', 'store', 'update']);
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RecoveryController;
use App\Http\Controllers\YearLevelController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SchoolYearController;
use Twilio\Rest\Client;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\LogController;

// Default Route
Route::view('/Change/Password', 'auth.change-password')->name('change-password');


// Unauthenticated Routes
Route::middleware(['unauth'])->group(function () {
    Route::view('/', 'auth.login')->name('login');
    Route::view('/Recover/Account', 'auth.confirm-otp')->name('recover');
});


// Process Routes
Route::post('/process/login', [AuthController::class, 'login'])->name('process-login');
Route::post('/process/logout', [AuthController::class, 'logout'])->name('process-logout');

Route::post('/process/create/account', [AccountController::class, 'createAccount'])->name('process-create-account');
Route::put('/process/edit/own/account', [AccountController::class, 'updateAccountOwnInfo'])->name('process-edit-account');
Route::put('/process/edit/other/account', [AccountController::class, 'updateAccountInfo'])->name('process-edit-instructor-account');
Route::post('/process/confirm/password', [AccountController::class, 'confirmPassword'])->name('process-confirm-password');
Route::put('/process/change/password', [AccountController::class, 'changePassword'])->name('process-change-password');
Route::delete('/process/delete/account', [AccountController::class, 'deleteInstructor'])->name('process-delete-instructor');

Route::post('/process/send/otp', [RecoveryController::class, 'sendOTP'])->name('process-send-otp');
Route::post('/process/confirm/otp', [RecoveryController::class, 'confirmOtp'])->name('process-confirm-otp');

Route::post('/process/create/department', [DepartmentController::class, 'createDepartment'])->name('process-create-department');
Route::put('/process/update/department', [DepartmentController::class, 'updateDepartment'])->name('process-edit-department');
Route::delete('/process/delete/department', [DepartmentController::class, 'deleteDepartment'])->name('process-delete-department');

Route::post('/process/create/program', [ProgramController::class, 'createProgram'])->name('process-create-program');
Route::put('/process/update/program', [ProgramController::class, 'updateProgram'])->name('process-edit-program');
Route::delete('/process/delete/program', [ProgramController::class, 'deleteProgram'])->name('process-delete-program');

Route::post('/process/create/year-level', [YearLevelController::class, 'createYearLevel'])->name('process-create-year-level');
Route::put('/process/update/year-level', [YearLevelController::class, 'updateYearLevel'])->name('process-edit-year-level');
Route::delete('/process/delete/year-level', [YearLevelController::class, 'deleteYearLevel'])->name('process-delete-year-level');

Route::post('/process/create/school-year', [SchoolYearController::class, 'createSchoolYear'])->name('process-create-school-year');
Route::put('/process/update/school-year', [SchoolYearController::class, 'updateSchoolYear'])->name('process-edit-school-year');
Route::delete('/process/delete/school-year', [SchoolYearController::class, 'deleteSchoolYear'])->name('process-delete-school-year');

Route::post('/process/create/section', [SectionController::class, 'createSection'])->name('process-create-section');
Route::put('/process/edit/section', [SectionController::class, 'updateSection'])->name('process-edit-section');
Route::delete('/process/delete/section', [SectionController::class, 'deleteSection'])->name('process-delete-section');

Route::post('/process/create/student-record', [StudentController::class, 'createStudentRecord'])->name('process-create-student-record');
Route::put('/process/edit/student-record', [StudentController::class, 'updateStudentRecord'])->name('process-edit-student-record');
Route::delete('/process/delete/student-record', [StudentController::class, 'deleteStudentRecord'])->name('process-delete-student-record');
Route::post('/students/import', [StudentController::class, 'importStudentRecord'])->name('process-import-student-record');

Route::post('/process/login/rfid', [MachineController::class, 'loginRFID'])->name('process-login-rfid');

Route::middleware('action')->group(function () {
    // Explicit GET routes to prevent unauthorized access via GET requests
    foreach (
        [
            'login',
            'logout',
            'create/account',
            'edit/own/account',
            'edit/other/account',
            'confirm/password',
            'change/password',
            'delete/account',
            'send/top',
            'confirm/otp',
            'create/department',
            'update/department',
            'delete/department',
            'create/program',
            'update/program',
            'delete/program',
            'create/year-level',
            'update/year-level',
            'delete/year-level',
            'create/school-year',
            'update/school-year',
            'delete/school-year',
            'create/section',
            'edit/section',
            'delete/section',
            'create/student-record',
            'edit/student-record',
            'delete/student-record',
            'import/student-record',
            'login/rfid',
            // 'delete/attendance',
        ] as $route
    ) {
        Route::get("/process/{$route}");
    }
});


// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::view('/Dashboard', 'page.dashboard')->name('dashboard');
    Route::view('/Edit/Account', 'auth.edit-account')->name('edit-account');
    Route::get('/Edit/Instructor/Account/{account_id}', [AccountController::class, 'loadAccount'])->name('edit-instructor-account');
    Route::view('/Confirm/Password', 'auth.confirm-password')->name('confirm-password');
    Route::get('/Manage/Students', [StudentController::class, 'index'])->name('student');
    Route::get('/get-programs/{department_id}', [StudentController::class, 'getPrograms'])->name('get-programs');
    Route::get('/get-sections/{program_id}', [StudentController::class, 'getSections'])->name('get-sections');
    Route::get('/get-year-levels/{program_id}', [StudentController::class, 'getYearLevels'])->name('get-year-levels');
    Route::get('/Manage/Accounts', [AccountController::class, 'index'])->name('instructor');
    Route::get('/Manage/Departments', [DepartmentController::class, 'index'])->name('department');
    Route::get('/Create/Account/Instructor', [AccountController::class, 'showCreateForm'])->name('create-instructor');
    Route::get('/Manage/Programs', [ProgramController::class, 'index'])->name('program');
    Route::get('/Manage/Sections', [SectionController::class, 'index'])->name('section');
    Route::get('/Manage/Year-Level', [YearLevelController::class, 'index'])->name('year-level');
    Route::get('/Manage/School-Year', [SchoolYearController::class, 'index'])->name('school-year');
    Route::get('/View/Attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::get('/View/Report', [ReportController::class, 'index'])->name('report');
    Route::post('/attendance/approve', [AttendanceController::class, 'approve'])->name('attendance.approve');
    Route::get('/instructor/record', [InstructorController::class, 'record'])->name('instructor.record');
   Route::post('/instructor/attendance/hide', [InstructorController::class, 'hide'])->name('instructor.attendance.hide');
Route::get('instructor/attendance/export', [InstructorController::class, 'exportCsv'])
    ->name('instructor.attendance.export');
    
    Route::get('/get-year-levels/by-department/{department}', [YearLevelController::class, 'byDepartment'])
    ->name('get-year-levels-by-department');
     Route::get('/Instructors/Create', [AccountController::class, 'showCreateForm']);
});




//Authenticated Machine Route
Route::middleware(['authmac'])->group(function () {
    Route::view('/Scan', 'page.machine.index')->name('scan');
    

});


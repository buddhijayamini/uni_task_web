<?php

use App\Http\Controllers\CourseSemesterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModuleController;
use Illuminate\Support\Facades\Auth;


// Authentication routes
Auth::routes();

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Dashboard route
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Course Routes
Route::group(['middleware' => ['auth']], function () {

    // Courses routes
    Route::group(['middleware' => ['permission:manage courses']], function () {
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{id}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');

        Route::get('/departments/{faculty}', [CourseController::class, 'getDepartments']);
    });

    Route::group(['middleware' => ['permission:access course']], function () {
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    });

    // Modules routes
    Route::group(['middleware' => ['permission:manage modules']], function () {
        Route::get('/courses/{courseId}/modules/create', [ModuleController::class, 'create'])->name('modules.create'); // Form to create a new module
        Route::post('/courses/{courseId}/modules', [ModuleController::class, 'store'])->name('modules.store'); // Store new module
        Route::get('/courses/{courseId}/modules/{moduleId}/edit', [ModuleController::class, 'edit'])->name('modules.edit'); // Form to edit a module
        Route::put('/courses/{courseId}/modules/{moduleId}', [ModuleController::class, 'update'])->name('modules.update'); // Update a module
        Route::delete('/courses/{courseId}/modules/{moduleId}', [ModuleController::class, 'destroy'])->name('modules.destroy'); // Delete a module
    });

    // Access modules
    Route::group(['middleware' => ['permission:access module']], function () {
        Route::get('/courses/{courseId}/modules', [ModuleController::class, 'index'])->name('modules.index'); // View all modules for a course
        Route::get('/courses/{courseId}/module/{moduleId}', [ModuleController::class, 'show'])->name('modules.show'); // Allow Teachers and Students to view individual modules
    });

    // Manage batch semester
    Route::group(['middleware' => ['permission:manage course semesters']], function () {
        Route::get('/courses/{courseId}/modules-list', [CourseSemesterController::class, 'getModules'])->name('course.modules');
        Route::get('/course-semester/create', [CourseSemesterController::class, 'create'])->name('course_semester.create');
        Route::get('/course-semester/{id}/edit', [CourseSemesterController::class, 'edit'])->name('course_semester.edit');
        Route::post('/course-semester', [CourseSemesterController::class, 'store'])->name('course_semester.store');
        Route::put('/course-semester/{id}', [CourseSemesterController::class, 'update'])->name('course_semester.update');
        Route::delete('/course-semester/{id}', [CourseSemesterController::class, 'destroy'])->name('course_semester.destroy');
    });

    // Access batch semester
    Route::group(['middleware' => ['permission:access course semester']], function () {
        Route::get('/course-semester', [CourseSemesterController::class, 'index'])->name('course_semester');
        Route::get('/course-semester/getModuleSemester/{courseId}/{semesterId}', [CourseSemesterController::class, 'getModuleSemester'])->name('course_semester.getModuleSemester');
        Route::get('/course-semester/student', [CourseSemesterController::class, 'viewStudentSemester'])->name('course_semester.student');
        Route::get('/course-semester/student/create', [CourseSemesterController::class, 'createStudentSemester'])->name('course_semester.student.create');
        Route::post('/course-semester/student/store', [CourseSemesterController::class,'storeStudentSemester'])->name('course_semester.student.store');

        Route::get('/courses/{courseId}/semesters/{semester}/{batchId}/credits', [CreditController::class, 'showCredits']);
        // Route to show the credits in a Blade view
        Route::get('/courses/{courseId}/semesters/{semester}/{batchId}/credits/view', [CreditController::class, 'viewCredits'])
            ->name('credits.view');
    });
});

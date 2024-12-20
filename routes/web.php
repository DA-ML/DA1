<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;

// Route hiển thị trang đăng nhập
Route::get('login', [Controller::class, 'showLoginForm'])->name('login');

// Route xử lý đăng nhập
Route::post('login', [Controller::class, 'login'])->name('login.submit');

// Route đăng xuất
// web.php
Route::post('/logout', [Controller::class, 'logout'])->name('logout');


// Route nhóm cho sinh viên
Route::middleware('check.login')->group(function () {
    Route::get('student/classlist', [StudentController::class, 'classList'])->name('student.classlist');

    Route::get('student/profile', [StudentController::class, 'studentProfile'])->name('student.profile');

    Route::get('student/password', [StudentController::class, 'studentPassword'])->name('student.password');
    Route::post('student/password', [StudentController::class, 'changeStudentPassword'])->name('student.password.change');

    Route::get('student/dashboard', [StudentController::class, 'studentDashboard'])->name('student.dashboard');

    Route::get('student/view/classes/{malop}', [StudentController::class, 'viewClass'])->name('student.class.details');

    Route::get('student/view/tests/{malop}', [StudentController::class, 'viewTest'])->name('student.class.tests');
    Route::get('student/test/{malop}/{msbkt}/redirect', [StudentController::class, 'redirectToTest'])->name('student.test.redirect');
    Route::get('student/test/{malop}/{msbkt}/form', [StudentController::class, 'takeTestForm'])->name('student.test.form');
    Route::get('student/test/{malop}/{msbkt}/essay', [StudentController::class, 'takeTestEssay'])->name('student.test.essay');


    Route::get('student/view/lectures/{malop}', [StudentController::class, 'viewLecture'])->name('student.class.lectures');
    Route::get('student/detail/lecture/{id}', [StudentController::class, 'show'])->name('student.lecture.detail');

    Route::get('student/view/members/{malop}', [StudentController::class, 'viewMember'])->name('student.class.members');

    Route::get('student/view/scores/{malop}', [StudentController::class, 'viewScore'])->name('student.class.scores');
});

// Route nhóm cho giáo viên
Route::middleware('check.login')->group(function () {
    Route::get('teacher/classlist', [TeacherController::class, 'classList'])->name('teacher.classlist');

    Route::get('teacher/profile', [TeacherController::class, 'teacherProfile'])->name('teacher.profile');

    Route::get('teacher/password', [TeacherController::class, 'teacherPassword'])->name('teacher.password');
    Route::post('teacher/password', [TeacherController::class, 'changePassword'])->name('teacher.password.change');

    Route::get('teacher/dashboard', [TeacherController::class, 'teacherDashboard'])->name('teacher.dashboard');

    Route::get('teacher/view/classes/{malop}', [TeacherController::class, 'viewClass'])->name('class.details');

    Route::get('teacher/view/tests/{malop}', [TeacherController::class, 'classTest'])->name('class.tests');

    Route::get('teacher/view/lectures/{malop}', [TeacherController::class, 'classLecture'])->name('class.lectures');

    Route::get('teacher/view/members/{malop}', [TeacherController::class, 'classMember'])->name('class.members');

    Route::get('teacher/view/statics/{malop}', [TeacherController::class, 'classStatics'])->name('class.statics');

    Route::get('teacher/view/scores/{malop}', [TeacherController::class, 'classScores'])->name('class.scores');

    Route::get('teacher/add/lecture/{malop}', [TeacherController::class, 'addLecture'])->name('add.lecture');
    Route::post('teacher/add/lecture/{malop}', [TeacherController::class, 'addLecture'])->name('add.lecture');

    Route::get('teacher/add/test/type/{malop}', [TeacherController::class, 'testType'])->name('test.type');

    Route::get('teacher/add/test/form/{malop}', [TeacherController::class, 'testForm'])->name('test.form');
    Route::post('teacher/add/test/store/{malop}', [TeacherController::class, 'storeTest'])->name('test.store');
    Route::post('teacher/add/test/storeessay/{malop}', [TeacherController::class, 'storetestEssay'])->name('test.store.essay');
    Route::delete('teacher/delete/test/{malop}/{msbkt}', [TeacherController::class, 'deleteTest'])->name('class.delete.test');

    Route::get('teacher/add/test/essay/{malop}', [TeacherController::class, 'testEssay'])->name('test.essay');

    Route::get('teacher/update/lecture/{malop}', [TeacherController::class, 'updateLecture'])->name('update.lecture');
    Route::delete('teacher/delete/lecture/{malop}/{id}', [TeacherController::class, 'deleteLecture'])->name('class.delete.lecture');
    Route::get('teacher/detail/lecture/{id}', [TeacherController::class, 'show'])->name('lecture.detail');
});

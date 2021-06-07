<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/pass', 'ChangePassController@executePass')->name('pass');


Route::get('/users/search', 'UserController@search')->name('search_user')->middleware('adminTeacher');
Route::post('/users/add', 'UserController@registerByAdmin')->name('register_by_admin')->middleware('admin');

Route::put('/users/next_semester/{semester}', 'UserController@passToNextSemester')->name('next_semester')->middleware('admin');
Route::post('/users/uploadBulkUsers', 'UserController@registerBulk')->name('register_bulk_users')->middleware('admin');
Route::put('/users/update/{id}/new_role={newRole}', 'UserController@updateUserRole')->name('update_role')->middleware('adminTeacher');
Route::delete('/users/deleteUser', 'UserController@deleteUser')->name('delete_user')->middleware('admin');
Route::get('/users', 'UserController@showAll')->name('users')->middleware('adminTeacher');

Route::get('/exam/prepare/database', 'ExamController@prepareDB')->name('prepare_DB_exam')->middleware('teacher');
Route::get('/exam/prepare', 'ExamController@prepareAny')->name('prepare_any_exam')->middleware('teacher');
Route::post('/exam/schedule', 'ExamController@scheduleDBExam')->name('schedule_DB_exam')->middleware('teacher');
Route::post('/exam/scheduleAny', 'ExamController@scheduleAnyExam')->name('schedule_any_exam')->middleware('teacher');
Route::get('/exam/fraud/steal_the_start/exam={examId}&user={userId}', 'ExamController@stealStart')->name('steal_start_exam');

Route::get('/exam/{examId}/result/{userId}', 'ExamController@showResult')->name('show_exam_result')->middleware('examResults');

Route::get('/exam/{examId}/modify/db', 'ExamController@modifyDbExam')->name('modify_db_exam')->middleware('teacher');
Route::get('/exam/{examId}/modify', 'ExamController@modifyAnyExam')->name('modify_any_exam')->middleware('teacher');
Route::put('/exam/update', 'ExamController@updateExam')->name('update_exam')->middleware('teacher');
Route::get('/exam/history', 'ExamController@history')->name('exams_history');

Route::get('/program/last30Days', 'ExamController@showLast30DaysExams')->name('show_last_30days_exams')->middleware('teacher');
Route::get('/program', 'ExamController@showExams')->name('show_exams');
Route::get('/exam/statistics', 'ExamController@filterExamStats')->name('filter_exam_stats')->middleware('teacher');
Route::get('/exam/statistics/{examId}', 'ExamController@showExamStats')->name('show_exam_stats')->middleware('teacher');
Route::put('/exam/promote_student/exam={examId}&user={userId}', 'ExamController@promoteStudent')->name('promote_student')->middleware('teacher');
Route::put('/exam/no_promote_student/exam={examId}&user={userId}', 'ExamController@undoPromoteStudent')->name('undo_promote_student')->middleware('teacher');
Route::get('/exam/statistics/search', 'ExamController@searchSubject')->name('search_user_from_exam_stats')->middleware('teacher');
Route::delete('/exam/allow_repeat/exam={examId}&user={userId}', 'SubjectController@allowRepeat')->name('allow_repeat')->middleware('teacher');

Route::get('/exam/{id}', 'SubjectController@generate')->name('generate_exam')->middleware('exam');
Route::post('/exam/increase_penalty', 'SubjectController@increasePenalty')->name('increase_penalty');
Route::post('/exam/correct', 'SubjectController@correctExam')->name('correct_exam');

Route::get('/course/prepare', 'CourseController@prepareNewCourse')->name('prepare_new_course');
Route::post('/course/add', 'CourseController@addNewCourse')->name('add_new_course')->middleware('admin');
Route::get('/course/all', 'CourseController@showCourses')->name('show_courses')->middleware('admin');
Route::get('/course/search', 'CourseController@search')->name('search_course')->middleware('admin');
Route::post('/course/addTeacher', 'DidacticController@addTeacherToCourse')->name('add_teacher_to_course')->middleware('admin');
Route::delete('/course/deleteTeacher', 'DidacticController@deleteTeacherFromCourse')->name('delete_teacher_from_course')->middleware('admin');
Route::delete('/course/{id}', 'CourseController@deleteCourse')->name('delete_course')->middleware('admin');

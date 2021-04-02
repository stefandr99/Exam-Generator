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
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/pass', 'ChangePassController@executePass')->name('pass');


Route::get('/users/search', 'UserController@search')->name('search_user');
Route::post('/users/add', 'UserController@registerByAdmin')->name('register_by_admin');

Route::put('/users/next_semester/{semester}', 'UserController@passToNextSemester')->name('next_semester');
Route::post('/users/uploadBulkUsers', 'UserController@registerBulk')->name('register_bulk_users');
Route::put('/users/update/{id}/newRole={newRole}', 'UserController@updateUserRole')->name('update_role');
Route::delete('/users/deleteUser', 'UserController@deleteUser')->name('delete_user');
Route::get('/users', 'UserController@showAll')->name('users');

Route::get('/exam/prepare', 'ExamController@prepare')->name('prepare_exam');
Route::post('/exam/schedule', 'ExamController@scheduleExam')->name('schedule_exam');
Route::get('/exam/fraud/{examId}/steal_the_start/{userId}', 'ExamController@stealStart')->name('steal_start_exam');
Route::get('/exam/{examId}/result/{userId}', 'ExamController@showResult')->name('show_partial_result');
Route::get('/exam/{examId}/modify', 'ExamController@modifyExam')->name('modify_exam');
Route::put('/exam/update', 'ExamController@updateExam')->name('update_exam');
Route::get('/program/last30Days', 'ExamController@showLast30DaysExams')->name('show_last_30days_exams');
Route::get('/program', 'ExamController@showExams')->name('show_exams');
Route::get('/exam/{examId}/statistics/temporal', 'ExamController@showTemporalStats')->name('show_exam_temporal_stats');


Route::get('/exam/{id}', 'SubjectController@generate')->name('generate_exam');
Route::post('/exam/increase_penalty', 'SubjectController@increasePenalty')->name('increase_penalty');
Route::post('/exam/timing', 'SubjectController@timing')->name('time_exam');
Route::post('/exam/correct', 'SubjectController@correctExam')->name('correct_exam');

Route::get('/course/prepare', 'CourseController@prepareNewCourse')->name('prepare_new_course');
Route::post('/course/add', 'CourseController@addNewCourse')->name('add_new_course');
Route::get('/course/all', 'CourseController@showCourses')->name('show_courses');
Route::get('/course/search', 'CourseController@search')->name('search_course');
Route::post('/course/addTeacher', 'DidacticController@addTeacherToCourse')->name('add_teacher_to_course');
Route::delete('/course/deleteTeacher', 'DidacticController@deleteTeacherFromCourse')->name('delete_teacher_from_course');

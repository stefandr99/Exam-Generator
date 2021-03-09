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

Route::get('/user/search', 'UserController@search')->name('search_user');
Route::get('/user/all', 'UserController@showAll')->name('users');
Route::post('/user/add', 'UserController@registerByAdmin')->name('register_by_admin');
Route::post('/user/uploadBulkUsers', 'UserController@registerBulk')->name('register_bulk_users');
Route::put('/user/update/{id}/newRole={newRole}', 'UserController@updateUserRole')->name('update_role');

Route::get('/exam/prepare', 'ExamController@prepare')->name('prepare_exam');
Route::post('/exam/correct', 'ExamController@correctPartial')->name('correct_partial');
Route::post('/exam/schedule', 'ExamController@scheduleExam')->name('schedule_exam');
Route::get('/exam/fraud/{examId}/steal_the_start/{userId}', 'ExamController@stealStart')->name('steal_start_exam');
Route::get('/exam/{examId}/result/{userId}', 'ExamController@showResult')->name('show_partial_result');
Route::get('/exam/{examId}/modify', 'ExamController@modifyExam')->name('modify_exam');
Route::put('/exam/update', 'ExamController@updateExam')->name('update_exam');
Route::get('/exam/{id}', 'ExamController@generate')->name('generate_exam');
Route::get('/program', 'ExamController@showExams')->name('show_exams');

Route::get('/course/prepare', 'CourseController@prepareNewCourse')->name('prepare_new_course');
Route::post('/course/add', 'CourseController@addNewCourse')->name('add_new_course');
Route::get('/course/all', 'CourseController@showCourses')->name('show_courses');
Route::get('/course/search', 'CourseController@search')->name('search_course');
Route::post('/course/addTeacher', 'CourseController@addTeacherToCourse')->name('add_teacher_to_course');
Route::delete('/course/deleteTeacher', 'CourseController@deleteTeacherFromCourse')->name('delete_teacher_from_course');

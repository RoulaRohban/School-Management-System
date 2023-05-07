<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('register', 'JWTAuthController@register');
    Route::post('login', 'JWTAuthController@login');
    Route::post('logout', 'JWTAuthController@logout');
    Route::post('refresh', 'JWTAuthController@refresh');
    Route::get('profile', 'JWTAuthController@profile');

});

Route::group( ['middleware' => 'auth:api'], function()
{
    ////prompt APIs

    Route::post('books', 'PromptController@addBook');
    Route::get('prompts/information', 'PromptController@getInformation');
    Route::get('books', 'PromptController@getBooks');
    Route::get('marks', 'PromptController@getMarks');
    Route::post('notes', 'PromptController@addNote');
    Route::post('behavior/marks', 'PromptController@addMark');
    Route::get('students', 'PromptController@getStudents');

    //Teacher APIs
    Route::get('teachers/information', 'TeacherController@getInformation');
    Route::post('homeworks', 'TeacherController@addHomework');
    Route::post('marks', 'TeacherController@addMark');
    Route::get('subject/{id}/homeworks', 'TeacherController@getHomeworksBySubjectId');

    //Student APIs
    Route::get('students/information', 'StudentController@getInformation');
    Route::get('schools', 'StudentController@getSchools');
    Route::post('hobbies', 'StudentController@addHobby');
    Route::get('subject/{id}/books', 'StudentController@getBooksBySubjectId');
    Route::get('subjects', 'StudentController@getSubjects');
    Route::get('subject/{id}/homeworks', 'StudentController@gethomeworksBySubjectId');
    Route::get('subject/{id}/mark', 'StudentController@getMarkBySubjectId');
    Route::get('subject/{id}/homework', 'StudentController@getHomeworkBySubjectId');
    Route::get('my-school/information', 'StudentController@getSchoolInformation');
    Route::post('students/register', 'StudentController@registerAtSchool');
    Route::post('change-password', 'StudentController@changePassword');

    //manager APIs
    Route::get('manager/information', 'ManagerController@getInformation');
    Route::get('teachers', 'ManagerController@getTeachers');
    Route::post('teachers', 'ManagerController@addTeacher');
    Route::delete('teachers/{id}', 'ManagerController@deleteTeacher');
    Route::get('prompts', 'ManagerController@getPrompts');
    Route::post('prompts', 'ManagerController@addPrompt');
    Route::delete('prompts/{id}', 'ManagerController@deletePrompt');
    Route::post('manager/notes', 'ManagerController@addNote');
    Route::get('manager/marks', 'ManagerController@getMarks');
    Route::get('manager/students', 'ManagerController@getStudents');
    Route::delete('students/{id}', 'ManagerController@deleteStudent');
    Route::post('students', 'ManagerController@addStudent');
    Route::get('accept/student/{id}', 'ManagerController@acceptStudent');

});



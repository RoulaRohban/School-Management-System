<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\Hobby\StoreHobbyRequest;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\models\Hobby;
use App\models\Homework;
use App\models\School;
use App\models\Student;
use App\models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Student']);
    }

    public function getInformation () {
        $userId = Auth::user()->id;
        $student = Student::where('user_id', $userId)->first();
        return response()->json([ 'student' => $student ],200);
    }

    public function getSchools () {
        $schools = School::get(['id','name','stars']);
        return response()->json([ 'schools' => $schools ],200);
    }

    public function addHobby (StoreHobbyRequest $request) {
        //get user student id
        $userId = Auth::user()->id;
        $studentId = Student::where('user_id', $userId)->pluck('id')->first();

        //add Hobby
        $validated_data = $request->validated();
        DB::beginTransaction();
        $validated_data = array_merge($validated_data, ['student_id' => $studentId ]);
        Hobby::create($validated_data);
        DB::commit();
        return response()->json([ 'msg' => 'Added Successfully' ],200);
    }

    public function getBooksBySubjectId ($id) {
        $subject = Subject::findOrFail($id);
        $books = $subject->books;
        return response()->json([ 'books' => $books ],200);
    }

    public function getSubjects () {
        $subjects = Subject::all();
        return response()->json([ 'subjects' => $subjects ],200);
    }

    public function gethomeworksBySubjectId ($id) {
        $date = Carbon::now()->format("Y-m-d");
        $homework = Homework::where('subject_id', $id)->where('expiredDate', '>=', $date)->get();
        return response()->json([ 'homework' => $homework ],200);
    }

    public function getMarkBySubjectId ($id) {
        $subject = Subject::findOrFail($id);
        //get user student id
        $userId = Auth::user()->id;
        $studentId = Student::where('user_id', $userId)->pluck('id')->first();
        $mark = $subject->students()->where('student_id', $studentId)->first()->pivot->mark;

        return response()->json([ 'mark' => $mark ],200);
    }

    public function getSchoolInformation () {
        //get user school id
        $userId = Auth::user()->id;
        $schoolId = Student::where('user_id', $userId)->pluck('school_id')->first();
        $school = School::findOrFail($schoolId);
        return response()->json([ 'school' => $school ],200);
    }

    public function registerAtSchool (StoreStudentRequest $request) {
        $validated_data = $request->validated();
        DB::beginTransaction();
        $image_identification_data = Helper::uploadFileTo($validated_data["identificationPath"], 'students/identifications');
        $image_familyBook_data = Helper::uploadFileTo($validated_data["familyBookPath"], 'students/familyBooks');
        $validated_data = array_merge($validated_data, [
            'identificationPath' => '/uploads/' . $image_identification_data["media_path"],
            'familyBookPath' => '/uploads/' . $image_familyBook_data["media_path"],
            'user_id' => Auth::user()->id ]);
        Student::create($validated_data);
        DB::commit();
        return response()->json([ 'msg' => 'Added Successfully' ],200);
    }

    public function changePassword(ChangePasswordRequest $request) {
        $validated_data = $request->validated();
        $user = Auth::user();
        $validated_data = array_merge($validated_data, [
            'password' => Hash::make($validated_data["password"]) ]);
        $user->update($validated_data);
        return response()->json([ 'msg' => 'Changed Password Successfully' ],200);
    }
}

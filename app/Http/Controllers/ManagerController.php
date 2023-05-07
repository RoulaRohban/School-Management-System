<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Prompt\StorePromptRequest;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Teacher\StoreTeacherRequest;
use App\models\Note;
use App\models\School;
use App\models\Student;
use App\models\Subject;
use App\models\Teacher;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Manager']);
    }

    public function getInformation () {
        $userId = Auth::user()->id;
        $manager = Teacher::where([
            'user_id'=> $userId,
            'type' => 'manager'
        ])->first();
        return response()->json([ 'manager' => $manager ],200);
    }

    public function getTeachers () {
        $teachers = Teacher::with('subject')
            ->where('type', 'teacher')->get();
        return response()->json([ 'teachers' => $teachers ],200);
    }

    public function addTeacher (StoreTeacherRequest $request) {
        $validated_data = $request->validated();
        DB::beginTransaction();

        //get school id
        $userId = Auth::user()->id;
        $schoolId = Teacher::where('user_id', $userId)->pluck('school_id')->first();

        //create user
        $password = Str::random(10);
        $teachersCount = Teacher::where(['school_id' => $schoolId, 'type' => 'teacher'])->count();
        $arrayUser = [
            'name' => $validated_data["name"] . '-teacher' . $teachersCount ,
            'email' => $validated_data["name"] . '_teacher' . $teachersCount . '@email.com',
        ];
        $user = User::create(array_merge($arrayUser, ['password' => bcrypt($password)]));
        $user->assignRole('Teacher');

        //Create Teacher
        $image_identification_data = Helper::uploadFileTo($validated_data["identificationPath"], 'teachers/identifications');
        $image_familyBook_data = Helper::uploadFileTo($validated_data["familyBookPath"], 'teachers/familyBooks');
        $validated_data = array_merge($validated_data, [
            'identificationPath' => '/uploads/' . $image_identification_data["media_path"],
            'familyBookPath' => '/uploads/' . $image_familyBook_data["media_path"],
            'school_id' => $schoolId,
            'type' => 'teacher',
            'user_id' => $user->id ]);
        $teacher = Teacher::create($validated_data);

        // Create Subject
        Subject::Create([
            'name' => $validated_data["subject_name"],
            'teacher_id' => $teacher->id
        ]);
        DB::commit();
        return response()->json([ 'msg' => 'Added Successfully', 'user' => $user, 'password' => $password ],200);
    }

    public function deleteTeacher ($id) {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();
        return response()->json([ 'msg' => 'Deleted Successfully' ],200);
    }

    public function getPrompts () {
        $prompts = Teacher::where('type', 'prompt')->get();
        return response()->json([ 'prompts' => $prompts ],200);
    }

    public function addPrompt (StorePromptRequest $request) {
        $validated_data = $request->validated();
        DB::beginTransaction();

        //get school id
        $userId = Auth::user()->id;
        $schoolId = Teacher::where('user_id', $userId)->pluck('school_id')->first();

        //create user
        $password = Str::random(10);
        $promptCount = Teacher::where(['school_id' => $schoolId, 'type' => 'prompt'])->count();
        $arrayUser = [
            'name' => $validated_data["name"] . '-prompt' . $promptCount ,
            'email' => $validated_data["name"] . '_prompt' . $promptCount . '@email.com',
        ];
        $user = User::create(array_merge($arrayUser, ['password' => bcrypt($password)]));
        $user->assignRole('Prompt');

        //Create Prompt
        $image_identification_data = Helper::uploadFileTo($validated_data["identificationPath"], 'prompts/identifications');
        $image_familyBook_data = Helper::uploadFileTo($validated_data["familyBookPath"], 'prompts/familyBooks');
        $validated_data = array_merge($validated_data, [
            'identificationPath' => '/uploads/' . $image_identification_data["media_path"],
            'familyBookPath' => '/uploads/' . $image_familyBook_data["media_path"],
            'school_id' => $schoolId,
            'type' => 'prompt',
            'user_id' => $user->id ]);
        Teacher::create($validated_data);

        DB::commit();
        return response()->json([ 'msg' => 'Added Successfully', 'user' => $user, 'password' => $password ],200);
    }

    public function deletePrompt ($id) {
        $prompt = Teacher::findOrFail($id);
        $prompt->delete();
        return response()->json([ 'msg' => 'Deleted Successfully' ],200);
    }

    public function addNote (StoreNoteRequest $request) {
        $validated_data = $request->validated();
        $userId = $userId = Auth::user()->id;
        $validated_data = array_merge($validated_data, ['user_id' => $userId]);
        Note::create($validated_data);
        return response()->json([ 'msg' => 'Added Successfully' ],200);
    }

    public function getMarks () {
        $marks = Student::with('subjects')->get();
        $totalMarks = Student::join('student_subjects', 'students.id','=','student_subjects.student_id')
            ->selectRaw('students.id, SUM(student_subjects.mark) AS TotalMark')
            ->groupBy('students.id')
            ->get();
        return response()->json([ 'marks' => $marks, 'totalMarks' => $totalMarks ],200);
    }

    public function getStudents () {
        $students = Student::all();
        return response()->json([ 'students' => $students ],200);
    }

    public function deleteStudent ($id) {
        $student = Student::findOrFail($id);
        $student->delete();
        return response()->json([ 'msg' => 'Deleted Successfully' ],200);
    }

    public function addStudent (StoreStudentRequest $request) {
        $validated_data = $request->validated();
        DB::beginTransaction();

        //school id
        $userId = Auth::user()->id;
        $schoolId = Teacher::where('user_id', $userId)->pluck('school_id')->first();

        //generate serial_number
        $studentCount = Student::where(['school_id' => $schoolId, 'acceptable' => 'yes'])->count();
        $schoolSerialNumber = School::where('id', $schoolId)->pluck('serial_number')->first();
        $serial_number = $schoolSerialNumber + $studentCount;

        //create user
        $password = Str::random(10);
        $arrayUser = [
            'name' => $validated_data["name"] . '-' . $serial_number,
            'email' => $validated_data["name"] . $validated_data["father_name"]  . $serial_number . '@email.com',
        ];
        $user = User::create(array_merge($arrayUser, ['password' => bcrypt($password)]));
        $user->assignRole('Student');

        //Create Student
        $image_identification_data = Helper::uploadFileTo($validated_data["identificationPath"], 'students/identifications');
        $image_familyBook_data = Helper::uploadFileTo($validated_data["familyBookPath"], 'students/familyBooks');
        $validated_data = array_merge($validated_data, [
            'identificationPath' => '/uploads/' . $image_identification_data["media_path"],
            'familyBookPath' => '/uploads/' . $image_familyBook_data["media_path"],
            'school_id' => $schoolId,
            'acceptable' => 'yes',
            'user_id' => $user->id,
            'serial_number' => $serial_number
        ]);
        Student::create($validated_data);
        DB::commit();

        return response()->json([ 'msg' => 'Added Successfully',
            'user' => $user, 'password' => $password, 'Serial Number' => $serial_number ],200);
    }

    public function acceptStudent ($id) {
        $student = Student::findOrFail($id);

        if($student->acceptable == 'yes') {
            return response()->json([ 'msg' => 'Student already Accepted'],200);
        }

        //school id
        $userId = Auth::user()->id;
        $schoolId = Teacher::where('user_id', $userId)->pluck('school_id')->first();

        //generate serial_number
        $studentCount = Student::where(['school_id' => $schoolId, 'acceptable' => 'yes'])->count();
        $schoolSerialNumber = School::where('id', $schoolId)->pluck('serial_number')->first();
        $serial_number = $schoolSerialNumber + $studentCount;

        $student->update([
            'acceptable' => 'yes',
            'serial_number' => $serial_number
        ]);
        return response()->json([ 'msg' => 'Student Accepted', 'Serial Number' => $serial_number ],200);
    }
}

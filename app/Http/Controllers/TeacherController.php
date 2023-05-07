<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\Homework\StoreHomeworkRequest;
use App\Http\Requests\Mark\StoreMarkRequest;
use App\models\Homework;
use App\models\StudentSubject;
use App\models\Subject;
use App\models\Teacher;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Teacher']);
    }

    public function getInformation () {
        $userId = Auth::user()->id;
        $teacher = Teacher::where([
            'user_id'=> $userId,
            'type' => 'teacher'
        ])->with('subject')->first();
        return response()->json([ 'teacher' => $teacher ],200);
    }

    public function addHomework (StoreHomeworkRequest $request) {
        //get user teacher id
        $userId = Auth::user()->id;
        $teacherId = Teacher::where('user_id', $userId)->pluck('id')->first();
        //get teacher's subject
        $subjectId = Subject::where('teacher_id', $teacherId)->pluck('id')->first();

        //add homework
        $validated_data = $request->validated();
        DB::beginTransaction();
        $image_data = Helper::uploadFileTo($validated_data["filePath"], 'homeworks');
        $validated_data = array_merge($validated_data, ['filePath' => '/uploads/' . $image_data["media_path"],
            'subject_id' => $subjectId ]);
        Homework::create($validated_data);
        DB::commit();
        return response()->json([ 'msg' => 'Added Successfully' ],200);
    }

    public function addMark (StoreMarkRequest $request) {
        //get user teacher id
        $userId = Auth::user()->id;
        $teacherId = Teacher::where('user_id', $userId)->pluck('id')->first();
        //get teacher's subject
        $subjectId = Subject::where('teacher_id', $teacherId)->pluck('id')->first();

        $validated_data = $request->validated();

        foreach ($validated_data["marks"] as $marks)
        {
            StudentSubject::create([
                'student_id' => $marks["student_id"],
                'mark' => $marks["mark"],
                'subject_id' => $subjectId,
            ]);
        }
        return response()->json([ 'msg' => 'Added Successfully' ],200);
    }

    public function getHomeworksBySubjectId ($id) {
        $subject = Subject::findOrFail($id);
        $homeworks = $subject->homeworks;
        return response()->json([ 'homeworks' => $homeworks ],200);
    }
}

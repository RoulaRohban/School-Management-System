<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Mark\StoreMarkRequest;
use App\Http\Requests\Note\StoreNoteRequest;
use App\models\Book;
use App\models\Note;
use App\models\Student;
use App\models\StudentSubject;
use App\models\Subject;
use App\models\Teacher;
use Illuminate\Support\Facades\DB;
use Auth;

class PromptController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Prompt']);
    }

    public function addBook (StoreBookRequest $request) {
        $validated_data = $request->validated();
        DB::beginTransaction();
        $image_data = Helper::uploadFileTo($validated_data["filePath"], 'books');
        $validated_data = array_merge($validated_data, ['filePath' => '/uploads/' . $image_data["media_path"]]);
        Book::create($validated_data);
        DB::commit();
        return response()->json([ 'msg' => 'Added Successfully' ],200);
    }

    public function getInformation () {
        $userId = Auth::user()->id;
        $prompt = Teacher::where([
            'user_id'=> $userId,
            'type' => 'prompt'
        ])->first();
        return response()->json([ 'prompt' => $prompt ],200);
    }

    public function getBooks () {
        $books = Book::all();
        return response()->json([ 'books' => $books ],200);
    }

    public function getMarks () {
        $marks = Student::with('subjects')->get();
        $totalMarks = Student::join('student_subjects', 'students.id','=','student_subjects.student_id')
            ->selectRaw('students.id, SUM(student_subjects.mark) AS TotalMark')
            ->groupBy('students.id')
            ->get();
        return response()->json([ 'marks' => $marks, 'totalMarks' => $totalMarks ],200);
    }

    public function addNote (StoreNoteRequest $request) {
        $validated_data = $request->validated();
        $userId = $userId = Auth::user()->id;
        $validated_data = array_merge($validated_data, ['user_id' => $userId]);
        Note::create($validated_data);
        return response()->json([ 'msg' => 'Added Successfully' ],200);
    }

    public function addMark (StoreMarkRequest $request) {
        $subjectId =Subject::where('name','behavior')->pluck('id')->first();
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

    public function getStudents () {
        $students = Student::get(['id','name','serial_number']);
        return response()->json([ 'students' => $students ],200);
    }

}

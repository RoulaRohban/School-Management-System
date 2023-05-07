<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class StudentSubject extends Model
{
    protected $guarded = ['id','created_at','updated_at'];

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function subject(){
        return $this->belongsTo(Subject::class);
    }
}

<?php

namespace App\models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = ['id','created_at','updated_at'];
    protected $hidden = ['created_at','updated_at'];

    public function school(){
        return $this->belongsTo(School::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function hobbies(){
        return $this->hasMany(Hobby::class);
    }

    public function subjects(){
        return $this->belongsToMany(Subject::class, StudentSubject::class)->withPivot('mark');
    }
}

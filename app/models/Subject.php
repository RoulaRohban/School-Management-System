<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $guarded = ['id','created_at','updated_at'];
    protected $hidden = ['created_at','updated_at'];

    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }

    public function books(){
        return $this->hasMany(Book::class);
    }

    public function homeworks(){
        return $this->hasMany(Homework::class);
    }

    public function students(){
        return $this->belongsToMany(Student::class, StudentSubject::class)->withPivot('mark');
    }
}

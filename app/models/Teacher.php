<?php

namespace App\models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $guarded = ['id','created_at','updated_at'];
    protected $hidden = ['created_at','updated_at'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function subject(){
        return $this->hasOne(Subject::class);
    }

    public function school(){
        return $this->belongsTo(School::class);
    }
}

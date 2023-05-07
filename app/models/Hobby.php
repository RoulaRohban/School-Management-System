<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Hobby extends Model
{
    protected $guarded = ['id','created_at','updated_at'];
    protected $hidden = ['created_at','updated_at'];

    public function student(){
        return $this->belongsTo(Student::class);
    }
}

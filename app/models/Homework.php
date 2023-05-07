<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    protected $guarded = ['id','created_at','updated_at'];

    public function subject(){
        return $this->belongsTo(Subject::class);
    }
}

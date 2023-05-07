<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = ['id','created_at','updated_at'];
    protected $hidden = ['created_at','updated_at'];
    protected $with = ['subject'];

    public function subject(){
        return $this->belongsTo(Subject::class);
    }
}

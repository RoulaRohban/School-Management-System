<?php

namespace App\models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $guarded = ['id','created_at','updated_at'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}

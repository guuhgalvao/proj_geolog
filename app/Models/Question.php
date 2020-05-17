<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $fillable = ['text'];
    protected $dates = ['deleted_at'];

    public function options(){
        return $this->hasMany('App\Models\QuestionsOption');
    }
}

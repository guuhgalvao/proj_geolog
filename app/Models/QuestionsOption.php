<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionsOption extends Model
{
    use SoftDeletes;

    protected $fillable = ['question_id', 'name', 'sequence', 'value'];
    protected $dates = ['deleted_at'];

    public function question(){
        return $this->belongsTo('App\Models\Question');
    }
}

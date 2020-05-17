<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResearchesProgress extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'research_id', 'sequence', 'status', 'img_path'];
    protected $dates = ['deleted_at'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}

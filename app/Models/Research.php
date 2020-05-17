<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Research extends Model
{
    use SoftDeletes;

    protected $fillable = ['sector_id', 'name'];
    protected $dates = ['deleted_at'];

    public function sector(){
        return $this->belongsTo('App\Models\Sector');
    }

    public function questions(){
        return $this->hasMany('App\Models\Question');
    }
}

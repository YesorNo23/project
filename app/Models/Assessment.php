<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    public $timestamps = false;
    protected $table = 'assessment_logs';
    protected $fillable = [ 'q1','q2','q3','q4','q5','predicted_mood','predicted_category','confidence','created_at' ];
}

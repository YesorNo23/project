<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    //
    public $timestamps = false;
    protected $table = 'application';
    protected $fillable = ['name','dir','detail','status'];

    
}

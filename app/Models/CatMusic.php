<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatMusic extends Model
{
    //
    protected $table ='cat_music';
    protected $fillable = ['musicid','catdetail_id','status'];
}

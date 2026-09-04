<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Music extends Model
{
    protected $table ='music';
    protected $fillable = ['name','image','detail','duration','creat_at','update_at','file_path','play_count','status'];

    // method เชื่มความสัมพันธ์กับตาราง filter แบบ one-to-many
    public function filter(): HasMany
    {
        return $this->hasMany(CatMusic::class, 'musicid', 'id');
    }

    // method เชื่มความสัมพันธ์กับตาราง filterdetail แบบ many-to-many
    public function filterDetails() 
    {
        return $this->belongsToMany(FilterDetail::class, 'cat_music', 'musicid', 'catdetail_id');
    }

}

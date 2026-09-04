<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class History extends Model
{
    //
    public $timestamps = false;
    protected $table = 'history';
    protected $fillable = ['uid','musicid','play_duration','created_at','status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }

    public function music()
    {
        return $this->belongsTo(Music::class, 'musicid', 'id');
    }
}

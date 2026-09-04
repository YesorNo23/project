<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Log extends Model
{
    //
    public $timestamps = false;
    protected $table = 'log';
    protected $fillable = ['uid','action','module','endpoint','ip_address','date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }
}

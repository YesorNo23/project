<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserInGroup extends Model
{
    //
    public $timestamps = false;
    protected $table = 'uig';
    protected $fillable = [
        'uid',
        'ugid',
        'status'
 
    ];

    // เชื่อมกลับไปหา User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }

    // เชื่อมกลับไปหา Group
    public function group(): BelongsTo
    {
        return $this->belongsTo(UserGroup::class, 'ugid', 'id');
    }
}

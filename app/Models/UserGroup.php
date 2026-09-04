<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserGroup extends Model
{
    public $timestamps = false;
    protected $table = 'usergroup';
    protected $fillable = [
        'name',
        'detail',
        'status'
 
    ];

    public function uig(): HasMany
    {
        return $this->hasMany(UserInGroup::class, 'ugid', 'id');
    }

    // 2. (แนะนำ) เชื่อมข้ามไปหา Users ทั้งหมดที่อยู่ในกลุ่มนี้
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'uig', 'ugid', 'uid');
    }

    public function apps()
    {
        // เชื่อมไปหา App ผ่านตารางกลาง acl โดยดึงค่า permission_level ออกมาด้วย
        return $this->belongsToMany(Application::class, 'acl', 'ugid', 'appid')
                    ->withPivot('acclevel');
    }
    
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    public $timestamps = false;
    protected $table = 'user';
    protected $fillable = [
        'username',
        'password',
        'name',
        'surname',
        'email',
        'birthdate',
        'gender',
        'usertype',
        'status'
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function uig(): HasMany
    {
        return $this->hasMany(UserInGroup::class, 'uid', 'id');
    }
                                                                                                                                 
    // 2. (แนะนำ) เชื่อมข้ามไปหา Group เลย จะใช้งานง่ายกว่ามาก
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(UserGroup::class, 'uig', 'uid', 'ugid');
    }

    // Helper function สำหรับเช็คสิทธิ์
    public function hasAppPermission($appDirection, $level)
    {
        // แนะนำให้แปลง $level เป็น (int) เพื่อความชัวร์ในการเปรียบเทียบค่า
        $requiredLevel = (int) $level;

        return $this->groups()->whereHas('apps', function ($query) use ($appDirection, $requiredLevel) {
            $query->where('application.dir', $appDirection) // ค้นหาชื่อแอป
                ->where('acl.acclevel', '>=', $requiredLevel); // เช็คว่าสิทธิ์ในตารางมีค่า >= สิทธิ์ที่ต้องการไหม
        })->exists();
    }
}

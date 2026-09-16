<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class FilterDetail extends Model
{
    //
    public $timestamps = false;
    protected $table = 'cat_detail';
    protected $fillable = [ 'category_id',
                            'name',
                            'status' ];

    public function music(): BelongsToMany
    {
        return $this->belongsToMany(Music::class, 'cat_music', 'catdetail_id', 'musicid');
    }
}

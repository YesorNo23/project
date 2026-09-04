<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Filter extends Model
{
    //
    public $timestamps = false;
    protected $table = 'category';
    protected $fillable = [ 'name',
                            'status' ];

    public function value(): HasMany
    {
        return $this->hasMany(FilterDetail::class, 'catagory_id', 'id');
    }

    
}

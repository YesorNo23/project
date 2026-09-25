<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class PlayList extends Model
{
    //
    protected $table = 'playlist';
    protected $fillable = ['uid','name','image','detail','created_at','updated_at','status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }

    public function music()
    {
        return $this->belongsToMany(Music::class, 'playlist_detail','playlistid', 'musicid')->withPivot('playlist_order','musicid')->orderByPivot('playlist_order', 'asc');
    }

    public function details()
    {
        return $this->hasMany(PlayListDetail::class, 'playlistid', 'id');
    }
}

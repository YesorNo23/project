<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayListDetail extends Model
{
    //
    public $timestamps = false;
    protected $table = 'playlist_detail';
    protected $fillable = ['playlistid','musicid','playlist_order','add_date','status'];
}

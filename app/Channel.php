<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'code',
    ];

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }
}

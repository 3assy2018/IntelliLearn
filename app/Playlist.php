<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'player',
        'code',
    ];

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function getEnrolledAttribute()
    {
        return auth()->check() ? (auth()->user()->playlists()->find($this->id) ? true : false) : false;
    }

    public function getAttendedAttribute()
    {
        return auth()->check()
            ? (auth()->user()->playlists()->find($this->id)->pivot->attendance->attended ? true : false)
            : false;
    }
}

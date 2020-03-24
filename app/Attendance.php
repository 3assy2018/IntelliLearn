<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'attended',
    ];

    protected $table = 'playlist_user_attendance';

    public function enrollment()
    {
        return $this->belongsTo(PlaylistUser::class, 'playlist_user_id');
    }
}

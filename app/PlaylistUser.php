<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PlaylistUser extends Pivot
{
    public function attendance()
    {
        return $this->hasOne(Attendance::class, 'playlist_user_id')
            ->where('created_at','>=', Carbon::today()->toDateTimeString());
    }
}

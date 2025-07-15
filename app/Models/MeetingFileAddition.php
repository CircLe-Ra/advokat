<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingFileAddition extends Model
{
    protected $guarded = ['id'];

    public function meetingSchedule()
    {
        return $this->belongsTo(MeetingSchedule::class);
    }

}

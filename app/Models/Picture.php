<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $fillable = ['announcement_id', 'url'];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }
}

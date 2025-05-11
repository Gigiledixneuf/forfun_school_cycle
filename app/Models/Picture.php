<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $fillable = [
        "url",
        "announcement_id"
    ];

    public function announcement(){
        return $this->belongsTo(Announcement::class);
    }
}

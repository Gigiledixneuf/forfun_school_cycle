<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    protected $fillable = [
        "user_id",
        "telephone",
        "profession",
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }
}

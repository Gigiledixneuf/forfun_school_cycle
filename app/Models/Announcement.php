<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'tutor_id',
        'category_id',
        'title',
        'description',
        'operation_type',
        'price',
        'state',
        'exchange_location_address',
        'exchange_location_longt',
        'exchange_location_lat'
    ];

    public function tutor(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function pictures(){
        return $this->hasMany(Picture::class);
    }

    public function favorites(){
        return $this->hasMany(Favorite::class);
    }
}

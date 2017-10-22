<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Establishment extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'photo', 'facebook', 'instagram', 'site', 'phone', 'address', 'lat', 'lng', 'categories_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at','created_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::Class, 'categories_id');
    }
}
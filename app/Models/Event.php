<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * CHECKBOX ARRAY
     *
     * @var array
     */
    protected $casts = [
        'items' => 'array'
    ];

    protected $dates = ['date'];

    /**
     * IGNORE NULL VALUES ON ARRAY
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * USER -> N EVENTS
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * COUNT USERS EVENTS
     *
     * @return void
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

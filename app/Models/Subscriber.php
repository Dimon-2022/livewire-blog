<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Subscriber extends Model
{
    use Notifiable;

    protected $fillable = [
        'email',
        'token',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    protected static function boot(){
        parent::boot();

        static::creating(function ($subscriber) {
            $subscriber->token = str()->random(60);
        });
    }

    //required for sending notifications to subscribers
    public function routeNotificationForMail($notification){
        return $this->email;
    }
}

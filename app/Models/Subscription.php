<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Subscription extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['email', 'price', 'currency', 'percentage', 'interval', 'last_notified'];

    protected $attributes = [
        'interval'      => null,
        'percentage'    => null,
        'last_notified' => null,
    ];
}

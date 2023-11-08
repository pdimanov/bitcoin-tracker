<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Subscription extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['email', 'price', 'currency', 'expiration_date', 'is_notified', 'is_increasing'];

    protected $attributes = [
        'expiration_date' => null,
        'is_notified'     => false,
        'is_increasing'   => true
    ];
}

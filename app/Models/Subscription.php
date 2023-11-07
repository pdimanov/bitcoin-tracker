<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'price', 'currency', 'expiration_date'];

    protected $attributes = [
        'expiration_date'  => null
    ];
}

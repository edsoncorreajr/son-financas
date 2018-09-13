<?php

namespace SONFin\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password'
    ];
    
}
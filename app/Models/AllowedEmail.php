<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowedEmail extends Model
{
    protected $fillable = ['email'];
    
    public static function isAllowed($email)
    {
        return static::where('email', $email)->exists();
    }
}

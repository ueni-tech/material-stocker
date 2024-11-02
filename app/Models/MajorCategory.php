<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MajorCategory extends Model
{
    protected $fillable = ['name'];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}

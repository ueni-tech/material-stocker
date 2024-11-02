<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinorCategory extends Model
{
    protected $fillable = ['name'];

    public function majorCategory()
    {
        return $this->belongsTo(MajorCategory::class);
    }

    public function images()
    {
        return $this->belongsToMany(Image::class)->withTimestamps();
    }
}

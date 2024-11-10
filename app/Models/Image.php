<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'major_category_id',
        'drive_file_id',
        'drive_view_link',
        'drive_download_link',
        'title',
        'user_name',
        'mime_type',
        'file_size',
        'description',
        'thumbnail_link'
    ];

    public function majorCategory()
    {
        return $this->belongsTo(MajorCategory::class);
    }

    public function minorCategories()
    {
        return $this->belongsToMany(MinorCategory::class)->withTimestamps();
    }

    public function getMimeTypeAttribute($value)
    {
        return explode('/', $value)[1];
    }

    public function getFileSizeAttribute($value)
    {
        return number_format($value / 1024, 1);
    }
}

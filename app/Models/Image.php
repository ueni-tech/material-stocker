<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

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
        $attribute = explode('/', $value)[1];
        switch ($attribute) {
            case 'jpeg':
                return 'jpg';
            case 'x-photoshop':
                return 'psd';
            case 'postscript':
                return 'ai';
            default:
                return $attribute;
        }
    }

    public function getFileSizeAttribute($value)
    {
        return number_format($value / 1024, 1);
    }
}

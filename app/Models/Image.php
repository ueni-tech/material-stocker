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
        'discription',
    ];

    public function majorCategory()
    {
        return $this->belongsTo('App\Models\MajorCategory');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $files = Image::with('majorCategory')->get()->map(function ($image) {
            $image->major_category = $image->majorCategory->name;
            $image->minor_categories = $image->minorCategories()->pluck('name')->toArray();
            return $image;
        })->sortByDesc('created_at');
        return view('index', compact('files'));
    }
}

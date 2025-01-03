<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $files = Image::with('majorCategory')->get()->map(function ($image) {
            $image->major_category = $image->majorCategory->name;
            $image->minor_categories = $image->minorCategories()->pluck('name')->toArray();
            $image->formatted_date = Carbon::parse($image->created_at)->format('Y年m月d日');
            $image->is_mine = $image->user_id === auth()->user()->id;
            return $image;
        })->sortByDesc('created_at');

        return view('pages.home', compact('files'));
    }
}

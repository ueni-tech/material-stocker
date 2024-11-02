<?php

namespace Database\Seeders;

use App\Models\MajorCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MajorCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $majorCategories = [
            '人物',
            '動物',
            '自然',
            '建物',
            '料理',
            '健康',
            '美容',
            'スポーツ',
            'その他',
        ];

        foreach ($majorCategories as $majorCategory) {
            MajorCategory::create(['name' => $majorCategory]);
        }
    }
}

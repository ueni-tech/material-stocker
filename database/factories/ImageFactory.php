<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'major_category_id' => 1,
            'drive_file_id' => $this->faker->uuid,
            'drive_view_link' => $this->faker->url,
            'drive_download_link' => $this->faker->url,
            'title' => $this->faker->sentence,
            'user_name' => $this->faker->name,
            'mime_type' => $this->faker->mimeType,
            'file_size' => $this->faker->numberBetween(1000, 1000000),
            'description' => $this->faker->paragraph,
            'thumbnail_link' => $this->faker->imageUrl,
        ];
    }
}

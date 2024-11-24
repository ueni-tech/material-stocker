<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed('MajorCategorySeeder');
    }

    public function testIndexReturnsCorrectView()
    {
        $image = Image::factory()->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHas('files', function ($files) use ($image) {
            return $files->contains($image);
        });
    }
}

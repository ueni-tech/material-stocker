<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Image;
use App\Models\User;
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
    $user = User::factory()->create();
    $image = Image::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertViewIs('pages.home');
    $response->assertViewHas('files', function ($files) use ($image) {
      return $files->contains($image);
    });
  }
}

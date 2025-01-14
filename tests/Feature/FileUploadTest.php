<?php

namespace Tests\Unit;

use App\Http\Requests\imageUpdateRequest;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Services\FileUploadService;
use App\Services\Google\GoogleDriveService;
use App\Http\Requests\imageUploadRequest;
use App\Models\Image;
use App\Models\MajorCategory;
use App\Models\MinorCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class FileUploadTest extends TestCase
{
  use RefreshDatabase;

  public function setUp(): void
  {
    parent::setUp();
    $this->seed('MajorCategorySeeder');
  }

  #[Test]
  public function FileUploadServiceのuploadの正常性テスト()
  {
    $user = User::factory()->create();
    $this->actingAs($user);

    $mockDriveService = Mockery::mock(GoogleDriveService::class);
    $mockDriveService->shouldReceive('uploadFile')
      ->once()
      ->andReturn([
        'file_id' => '12345',
        'web_view_link' => 'http://example.com/view',
        'web_content_link' => 'http://example.com/download',
        'name' => 'testfile.jpg',
        'mimeType' => 'image/jpeg',
        'size' => 1024,
        'thumbnail_link' => 'http://example.com/thumbnail',
        'created_time' => '2023-10-01T00:00:00Z',
        'modified_time' => '2023-10-01T00:00:00Z',
      ]);

    $fileUploadService = new FileUploadService($mockDriveService);

    $mockRequest = Mockery::mock(imageUploadRequest::class);
    $mockRequest->shouldReceive('user')->andReturn($user);
    $mockRequest->shouldReceive('file')->with('file')->andReturn('dummy_file');
    $mockRequest->shouldReceive('major_category_id')->andReturn(1);
    $mockRequest->shouldReceive('input')->with('minorCategories')->andReturn(['Category1', 'Category2']);
    $mockRequest->shouldReceive('input')->with('description')->andReturn('Test description');
    $mockRequest->shouldReceive('all')->andReturn([
      'file' => 'dummy_file',
      'major_category_id' => 1,
      'minorCategories' => ['Category1', 'Category2'],
      'description' => 'Test description'
    ]);

    $response = $fileUploadService->fileUpload($mockRequest);

    $this->assertTrue($response['success']);
    $this->assertEquals('12345', $response['file_id']);
    $this->assertEquals('http://example.com/view', $response['file_url']);
    $this->assertEquals('testfile.jpg', $response['file_name']);
    $this->assertEquals('image/jpeg', $response['file_type']);
    $this->assertEquals(1024, $response['file_size']);
    $this->assertEquals('Test description', $response['description']);
  }

  #[Test]
  public function FileUploadServiceのfileUpdateの正常性テスト()
  {
    // ユーザーとカテゴリーの作成
    $user = User::factory()->create();
    $major_category_id = MajorCategory::first()->id;
    $another_major_category_id = MajorCategory::where('id', '!=', $major_category_id)->first()->id;

    // テスト対象のユーザーとして作成したユーザーを設定
    $this->actingAs($user);

    // GoogleDriveServiceのモックを作成
    $mockDriveService = Mockery::mock(GoogleDriveService::class);

    // FileUploadServiceのインスタンスを作成
    $fileUploadService = new FileUploadService($mockDriveService);

    // テスト対象のユーザーが所有するイメージを作成
    $image = Image::factory()->create([
      'user_id' => $user->id,
      'major_category_id' => $major_category_id,
      'thumbnail_link' => 'http://example.com/thumbnail',
    ]);

    // imageUpdateRequestのモックを作成
    $mockRequest = Mockery::mock(imageUpdateRequest::class);
    $mockRequest->shouldReceive('major_category_id')->andReturn($another_major_category_id);
    $mockRequest->shouldReceive('input')->with('minorCategories')->andReturn(['Category1', 'Category2']);
    $mockRequest->shouldReceive('input')->with('description')->andReturn('Test description');
    $mockRequest->shouldReceive('all')->andReturn([
      'major_category_id' => $another_major_category_id,
      'minorCategories' => ['Category1', 'Category2'],
      'description' => 'Test description'
    ]);

    // fileUpdateメソッドを呼び出し、そのレスポンスを検証
    $response = $fileUploadService->fileUpdate($mockRequest, $image->id);

    // レスポンスが成功していることを確認
    $this->assertTrue($response['success']);

    // データベースから更新されたイメージを取得
    $updatedImage = Image::find($image->id);

    // major_category_idが更新されていることを確認
    $this->assertEquals($another_major_category_id, $updatedImage->major_category_id);

    // descriptionが更新されていることを確認
    $this->assertEquals('Test description', $updatedImage->description);

    // minorCategoriesが更新されていることを確認
    $minorCategories = $updatedImage->minorCategories;
    $this->assertEquals(2, $minorCategories->count());
    $this->assertTrue($minorCategories->contains('name', 'Category1'));
    $this->assertTrue($minorCategories->contains('name', 'Category2'));
  }


  #[Test]
  public function FileUploadServiceのdeleteFileの正常性テスト()
  {
    $user = User::factory()->create();
    $major_category_id = MajorCategory::first()->id;

    $this->actingAs($user);

    $mockDriveService = Mockery::mock(GoogleDriveService::class);
    $mockDriveService->shouldReceive('deleteFile')
      ->once()
      ->andReturn(true);

    $fileUploadService = new FileUploadService($mockDriveService);

    $image = Image::factory()->create([
      'user_id' => $user->id,
      'major_category_id' => $major_category_id,
      'thumbnail_link' => 'http://example.com/thumbnail',
    ]);

    $response = $fileUploadService->deleteFile($image->id);

    $this->assertTrue($response['success']);
  }
}

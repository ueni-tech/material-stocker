<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Services\FileUploadService;
use App\Services\Google\GoogleDriveService;
use App\Http\Requests\imageUploadRequest;
use App\Models\Image;
use App\Models\MinorCategory;
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
    public function FileUploadServiceの正常性テスト()
    {
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
}

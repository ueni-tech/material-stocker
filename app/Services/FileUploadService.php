<?php

namespace App\Services;

use App\Http\Requests\imageUploadRequest;
use App\Models\Image;
use App\Services\Google\GoogleDriveService;
use App\Services\Google\Exceptions\GoogleDriveException;

class FileUploadService
{
  private GoogleDriveService $driveService;

  public function __construct(GoogleDriveService $driveService)
  {
    $this->driveService = $driveService;
  }

  public function fileUpload(imageUploadRequest $request)
  {
    $file = $request->file('file');
    $majorCategoryId = $request->major_category_id;
    $description = $request->input('description');
    try {
      $result = $this->driveService->uploadFile($file);

      Image::create([
        'major_category_id' => $majorCategoryId,
        'drive_file_id' => $result['file_id'],
        'drive_view_link' => $result['web_view_link'],
        'drive_download_link' => $result['web_content_link'],
        'title' => $result['name'],
        'user_name' => 'ユーザーさん',
        'mime_type' => $result['mimeType'],
        'file_size' => $result['size'],
        'description' => $description,
        'thumbnail_link' => $result['thumbnail_link']
      ]);

      return [
        'success' => true,
        'file_id' => $result['file_id'],
        'file_url' => $result['web_view_link'],
        'file_name' => $result['name'],
        'file_type' => $result['mimeType'],
        'file_size' => $result['size'],
        'created_time' => $result['created_time'],
        'modified_time' => $result['modified_time'],
        'description' => $description,
        'web_content_link' => $result['web_content_link'],
        'thumbnail_link' => $result['thumbnail_link']
      ];
    } catch (GoogleDriveException $e) {
      return [
        'success' => false,
        'error' => 'アップロードに失敗しました: ' . $e->getMessage()
      ];
    }
  }
}

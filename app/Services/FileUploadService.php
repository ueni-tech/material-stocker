<?php

namespace App\Services;

use App\Services\Google\GoogleDriveService;
use App\Services\Google\Exceptions\GoogleDriveException;

class FileUploadService
{
  private GoogleDriveService $driveService;

  public function __construct(GoogleDriveService $driveService)
  {
    $this->driveService = $driveService;
  }

  public function fileUpload($file)
  {
    try {
      $result = $this->driveService->uploadFile($file);
      return [
        'success' => true,
        'file_id' => $result['file_id'],
        'file_url' => $result['web_view_link'],
        'file_name' => $result['name'],
        'file_type' => $result['mimeType'],
        'file_size' => $result['size'],
        'created_time' => $result['created_time'],
        'modified_time' => $result['modified_time'],
        'description' => $result['description'],
        'web_content_link' => $result['web_content_link']
      ];
    } catch (GoogleDriveException $e) {
      return [
        'success' => false,
        'error' => 'アップロードに失敗しました: ' . $e->getMessage()
      ];
    }
  }
}

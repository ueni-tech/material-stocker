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
        'file_url' => $result['view_link']
      ];
    } catch (GoogleDriveException $e) {
      return [
        'success' => false,
        'error' => 'アップロードに失敗しました: ' . $e->getMessage()
      ];
    }
  }
}

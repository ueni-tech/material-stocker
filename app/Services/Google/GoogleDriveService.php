<?php

namespace App\Services\Google;

use Google_Client;
use Google_Service_Drive;
use Illuminate\Support\Facades\Log;
use App\Services\Google\Exceptions\GoogleDriveException;

class GoogleDriveService
{
    private $service;
    private $folderId;

    public function __construct()
    {
        $this->initialize();
    }

    private function initialize(): void
    {
        try {
            $client = new Google_Client();
            
            $authType = config('services.google.auth_type', 'service-account');
            
            if ($authType === 'service-account') {
                $this->initializeServiceAccount($client);
            } else {
                $this->initializeOAuth($client);
            }
            
            $this->service = new Google_Service_Drive($client);
            $this->folderId = config('services.google.folder_id');
            
        } catch (\Exception $e) {
            Log::error('Google Drive初期化エラー', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new GoogleDriveException('Google Driveの初期化に失敗しました: ' . $e->getMessage());
        }
    }

    private function initializeServiceAccount(Google_Client $client): void
    {
        $credentialsPath = config('services.google.credentials_path');
        
        if (!file_exists($credentialsPath)) {
            throw new GoogleDriveException('認証ファイルが見つかりません: ' . $credentialsPath);
        }

        $client->setAuthConfig($credentialsPath);
        $client->addScope(Google_Service_Drive::DRIVE);
    }

    private function initializeOAuth(Google_Client $client): void
    {
        // OAuth初期化処理（必要な場合）
    }

    public function uploadFile($file, ?string $subFolder = null): array
    {
        try {
            $parentId = $subFolder ? 
                $this->getOrCreateFolder($subFolder) : 
                $this->folderId;

            $fileMetadata = new \Google_Service_Drive_DriveFile([
                'name' => $file->getClientOriginalName(),
                'parents' => [$parentId]
            ]);

            $result = $this->service->files->create(
                $fileMetadata,
                [
                    'data' => file_get_contents($file->getRealPath()),
                    'uploadType' => 'multipart',
                    'fields' => 'id, webViewLink, name'
                ]
            );

            return [
                'file_id' => $result->id,
                'view_link' => $result->webViewLink,
                'name' => $result->name
            ];

        } catch (\Exception $e) {
            Log::error('ファイルアップロードエラー', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName()
            ]);
            throw new GoogleDriveException('ファイルのアップロードに失敗しました: ' . $e->getMessage());
        }
    }

    private function getOrCreateFolder(string $folderName): string
{
    $query = sprintf("name = '%s' and mimeType = 'application/vnd.google-apps.folder' and trashed = false", $folderName);

    $response = $this->service->files->listFiles([
        'q' => $query,
        'spaces' => 'drive',
        'fields' => 'files(id, name)',
    ]);

    if (count($response->files) > 0) {
        return $response->files[0]->id;
    }

    $fileMetadata = new \Google_Service_Drive_DriveFile([
        'name' => $folderName,
        'mimeType' => 'application/vnd.google-apps.folder',
        'parents' => [$this->folderId]
    ]);

    $folder = $this->service->files->create($fileMetadata, [
        'fields' => 'id'
    ]);

    return $folder->id;
}
}

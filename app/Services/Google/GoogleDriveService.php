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

    public function uploadFile($file, ?string $subFolder = null, ?string $discription = null): array
    {
        try {
            $parentId = $subFolder ?
                $this->getOrCreateFolder($subFolder) :
                $this->folderId;

            $fileMetadata = new \Google_Service_Drive_DriveFile([
                'name' => $file->getClientOriginalName(),
                'parents' => [$parentId],
                'description' => $discription
            ]);

            $result = $this->service->files->create(
                $fileMetadata,
                [
                    'data' => file_get_contents($file->getRealPath()),
                    'uploadType' => 'multipart',
                    'fields' => 'id, webViewLink, name, mimeType, size, createdTime, modifiedTime, description, webContentLink, thumbnailLink',
                ]
            );

            $this->makeFilePublic($result->id);

            $file = $this->waitForThumbnail($result->id);

            return [
                'file_id' => $file->id,
                'web_view_link' => $file->webViewLink,
                'name' => $file->name,
                'mimeType' => $file->mimeType,
                'size' => $file->size,
                'created_time' => $file->createdTime,
                'modified_time' => $file->modifiedTime,
                'description' => $file->description,
                'web_content_link' => $file->webContentLink,
                'thumbnail_link' => $file->thumbnailLink
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

    private function waitForThumbnail(string $fileId): \Google_Service_Drive_DriveFile
    {
        $maxAttempts = 10;
        $attempt = 0;
        $waitTime = 2; // 秒

        while ($attempt < $maxAttempts) {
            $file = $this->service->files->get($fileId, [
                'fields' => 'id, webViewLink, name, mimeType, size, createdTime, modifiedTime, description, webContentLink, thumbnailLink',
                'supportsAllDrives' => true
            ]);

            if (!empty($file->thumbnailLink)) {
                return $file;
            }

            sleep($waitTime);
            $attempt++;
        }

        throw new GoogleDriveException('サムネイルリンクの生成に失敗しました。');
    }

    private function makeFilePublic(string $fileId): void
    {
        $permission = new \Google_Service_Drive_Permission([
            'type' => 'anyone',
            'role' => 'reader',
        ]);

        $this->service->permissions->create($fileId, $permission, [
            'fields' => 'id',
        ]);
    }

    public function deleteFile(string $fileId): void
    {
        try {
            $this->service->files->delete($fileId);
        } catch (\Exception $e) {
            Log::error('ファイル削除エラー', [
                'error' => $e->getMessage(),
                'file_id' => $fileId
            ]);
            throw new GoogleDriveException('ファイルの削除に失敗しました: ' . $e->getMessage());
        }
    }
}

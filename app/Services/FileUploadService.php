<?php

namespace App\Services;

use App\Http\Requests\imageUpdateRequest;
use App\Http\Requests\imageUploadRequest;
use App\Models\Image;
use App\Models\MajorCategory;
use App\Models\MinorCategory;
use App\Services\Google\GoogleDriveService;
use App\Services\Google\Exceptions\GoogleDriveException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    private GoogleDriveService $driveService;

    public function __construct(GoogleDriveService $driveService)
    {
        $this->driveService = $driveService;
    }

    private function saveThumbnail(string $thumbnailUrl, string $fileName): string
    {
        try {
            // サムネイル画像をダウンロード
            $thumbnailContent = file_get_contents($thumbnailUrl);
            if ($thumbnailContent === false) {
                throw new \Exception('サムネイル画像のダウンロードに失敗しました');
            }

            // 保存用のファイル名を生成（ユニークな名前にする）
            $extension = 'jpg'; // Google DriveのサムネイルはJPEG形式
            $thumbnailFileName = 'thumbnails/' . Str::uuid() . '.' . $extension;

            // Storage::putでファイルを保存（publicディスクを使用）
            if (!Storage::disk('public')->put($thumbnailFileName, $thumbnailContent)) {
                throw new \Exception('サムネイル画像の保存に失敗しました');
            }

            // 保存したファイルのパスを返す
            return $thumbnailFileName;
        } catch (\Exception $e) {
            \Log::error('サムネイル保存エラー', [
                'error' => $e->getMessage(),
                'file_name' => $fileName
            ]);
            return ''; // エラーの場合は空文字を返す
        }
    }

    public function fileUpload(imageUploadRequest $request)
    {
        $file = $request->file('file');
        $majorCategoryId = $request->major_category_id;
        $majorCategoryName = MajorCategory::find($majorCategoryId)->name;
        $minorCategories = $request->input('minorCategories');
        $description = $request->input('description');

        try {
            $result = $this->driveService->uploadFile($file, $majorCategoryName);

            // サムネイルを保存
            $thumbnailPath = '';
            if (!empty($result['thumbnail_link'])) {
                $thumbnailPath = $this->saveThumbnail(
                    $result['thumbnail_link'],
                    $result['name']
                );
            }

            $image = Image::create([
                'user_id' => $request->user()->id,
                'major_category_id' => $majorCategoryId,
                'drive_file_id' => $result['file_id'],
                'drive_view_link' => $result['web_view_link'],
                'drive_download_link' => $result['web_content_link'],
                'title' => $result['name'],
                'user_name' => $request->user()->name,
                'mime_type' => $result['mimeType'],
                'file_size' => $result['size'],
                'description' => $description,
                'thumbnail_link' => $thumbnailPath ? Storage::disk('public')->url($thumbnailPath) : $result['thumbnail_link']
            ]);

            if ($minorCategories) {
                foreach ($minorCategories as $minorCategoryName) {
                    $minorCategory = MinorCategory::firstOrCreate([
                        'name' => $minorCategoryName,
                    ]);
                    $image->minorCategories()->attach($minorCategory->id);
                }
            }

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
                'thumbnail_link' => $image->thumbnail_link
            ];
        } catch (GoogleDriveException $e) {
            return [
                'success' => false,
                'error' => 'アップロードに失敗しました: ' . $e->getMessage()
            ];
        }
    }

    public function fileupdate(imageUpdateRequest $request, $id)
    {
        $image = Image::find($id);
        $majorCategoryId = $request->major_category_id;
        $minorCategories = $request->input('minorCategories');
        $description = $request->input('description');

        $image->update([
            'major_category_id' => $majorCategoryId,
            'description' => $description
        ]);

        $image->minorCategories()->detach();
        if ($minorCategories) {
            foreach ($minorCategories as $minorCategoryName) {
                $minorCategory = MinorCategory::firstOrCreate([
                    'name' => $minorCategoryName,
                ]);
                $image->minorCategories()->attach($minorCategory->id);
            }
        }

        return [
            'success' => true,
        ];
    }

    public function deleteFile($id)
    {
        $image = Image::find($id);
        try {
            // サムネイル画像の削除
            if ($image->thumbnail_link && !Str::startsWith($image->thumbnail_link, 'https://drive.google.com')) {
                $path = str_replace(Storage::disk('public')->url(''), '', $image->thumbnail_link);
                Storage::disk('public')->delete($path);
            }

            $this->driveService->deleteFile($image->drive_file_id);
            $image->delete();

            return [
                'success' => true,
                'file_id' => $image->drive_file_id,
                'file_name' => $image->title
            ];
        } catch (GoogleDriveException $e) {
            return [
                'success' => false,
                'error' => '削除に失敗しました: ' . $e->getMessage()
            ];
        }
    }
}

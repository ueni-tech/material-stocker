<?php

namespace App\Http\Controllers;

use App\Http\Requests\imageUploadRequest;
use App\Models\Image;
use App\Models\MajorCategory;
use App\Services\FileUploadService;

class FileController extends Controller
{
    private FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function upload()
    {
        $majorCategories = MajorCategory::all();
        return view('pages.upload', compact('majorCategories'));
    }

    public function fileUpload(imageUploadRequest $request)
    {
        $result = $this->fileUploadService->fileUpload($request);

        if ($result['success']) {
            return redirect()
                ->route('files.upload')
                ->with('success', 'ファイルのアップロードが完了しました');
        } else {
            return redirect()
                ->route('files.upload')
                ->withErrors($result['error']);
        }
    }

    public function deleteFile($id)
    {
        $result = $this->fileUploadService->deleteFile($id);

        if ($result['success']) {
            return redirect()
                ->route('home')
                ->with('success', 'ファイルの削除が完了しました');
        } else {
            return redirect()
                ->route('home')
                ->withErrors($result['error']);
        }
    }
}

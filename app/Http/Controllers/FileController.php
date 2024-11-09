<?php

namespace App\Http\Controllers;

use App\Http\Requests\imageUploadRequest;
use App\Services\FileUploadService;

class FileController extends Controller
{
    private FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function fileUpload(imageUploadRequest $request)
    {
        $result = $this->fileUploadService->fileUpload($request->file('file'));

        if ($result['success']) {
            return redirect()
                ->route('files.upload')
                ->with('success', 'ファイルのアップロードが完了しました')
                ->with('file_url', $result['file_url'])
                ->with("web_content_link", $result['web_content_link'])
                ->with("thumbnail_link", $result['thumbnail_link']);
        } else {
            return redirect()
                ->route('files.upload')
                ->withErrors($result['error']);
        }
    }
}

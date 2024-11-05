<?php

namespace App\Http\Controllers;

use App\Http\Requests\imageUploadRequest;
use Illuminate\Http\Request;

use App\Services\Google\GoogleDriveService;
use App\Services\Google\Exceptions\GoogleDriveException;

class FileController extends Controller
{
    private GoogleDriveService $driveService;

    public function __construct(GoogleDriveService $driveService)
    {
        $this->driveService = $driveService;
    }

    public function upload(Request $request)
    {
        try {
            $result = $this->driveService->uploadFile($request->file('file'));
            
            return response()->json([
                'success' => true,
                'data' => $result
            ]);
            
        } catch (GoogleDriveException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function simpleUpload(imageUploadRequest $request)
    {
        try {
            $result = $this->driveService->uploadFile($request->file('file'), 'test-folder');
            
            return redirect()
                ->route('files.simple-upload')
                ->with('success', 'ファイルのアップロードが完了しました')
                ->with('file_url', $result['view_link']);
                
        } catch (GoogleDriveException $e) {
            return redirect()
                ->route('files.simple-upload')
                ->withErrors('アップロードに失敗しました: ' . $e->getMessage());
        }
    }
}

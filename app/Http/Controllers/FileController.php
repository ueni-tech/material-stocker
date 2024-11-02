<?php

namespace App\Http\Controllers;

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

    public function simpleUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB制限の例
        ]);

        try {
            $result = $this->driveService->uploadFile($request->file('file'));
            
            return redirect()
                ->route('files.upload')
                ->with('success', 'ファイルのアップロードが完了しました')
                ->with('file_url', $result['view_link']);
                
        } catch (GoogleDriveException $e) {
            return redirect()
                ->route('files.upload')
                ->withErrors('アップロードに失敗しました: ' . $e->getMessage());
        }
    }
}

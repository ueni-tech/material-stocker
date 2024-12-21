<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>

<body>
    <h1 class="text-3xl">アップロード済み画像</h1>
    <div class="grid grid-cols-3 gap-4">
        @foreach ($files as $file)
        <div class="bg-gray-100 p-4">
            <div>
                <img src="{{ $file->thumbnail_link }}" alt="">
                <ul>
                    <li>{{ $file->title }}</li>
                    <li><span>アップロード：</span><span>{{ $file->user_name }}</span></li>
                    <li><span>アップロード日：</span><span>{{ $file->created_at }}</span></li>
                    <li><span>カテゴリー：</span><span>{{ $file->major_category }}</span></li>
                    <li>{{ $file->mime_type }}</li>
                    <li>{{ $file->file_size }}KB</li>
                    <li>
                        <div class="flex flex-wrap">
                            @if($file->minor_categories)
                            @foreach ($file->minor_categories as $minor_category)
                            <span class="inline-block bg-blue-100 text-blue-700 text-xs rounded-full px-2 py-1 mr-2">
                                {{ $minor_category }}
                            </span>
                            @endforeach
                            @else
                            <span class="inline-block bg-gray-300 text-black text-xs rounded-full px-2 py-1 mr-2">
                                キーワードなし
                            </span>
                            @endif
                        </div>
                    </li>
                    <li><span>説明：</span><span>{{ $file->description }}</span></li>
                </ul>
            </div>
            <a href="{{ $file->drive_download_link }}" class="inline-block bg-teal-500 text-white p-2 rounded">ダウンロード</a>
        </div>
        @endforeach
    </div>

    <div class="fixed bottom-8 right-8">
        <a href="{{ route('files.upload') }}" class="bg-blue-500 text-white p-2 rounded">アップロードはこちら</a>
    </div>
</body>

</html>

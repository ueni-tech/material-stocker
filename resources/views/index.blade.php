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
            <a href="{{ $file->drive_download_link }}">
                <figure>
                    <img src="{{ $file->thumbnail_link }}" alt="">
                    <figcaption>
                        <ul>
                            <li>{{ $file->title }}</li>
                            <li><span>アップロード：</span><span>{{ $file->user_name }}</span></li>
                            <li><span>アップロード日：</span><span>{{ $file->created_at }}</span></li>
                            <li>{{ $file->major_category }}</li>
                            <li>{{ $file->mime_type }}</li>
                            <li>{{ $file->file_size }}KB</li>
                            <li>{{ $file->description }}</li>
                        </ul>
                    </figcaption>
                </figure>
            </a>
        </div>
        @endforeach
    </div>
</body>

</html>

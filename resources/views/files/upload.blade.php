<!-- resources/views/files/upload.blade.php -->
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ファイルアップロード</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
  <div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
      <h1 class="text-2xl font-bold text-center mb-8">ファイルアップロード</h1>

      @if (session('success'))
      <div class="mb-4 bg-green-50 border-l-4 border-teal-400 p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-teal-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
            <a href="{{ route('home') }}" class="text-blue-500 hover:underline">一覧に戻る</a>
          </div>
        </div>
      </div>
      @endif

      @if ($errors->any())
      <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <ul class="text-sm text-red-700">
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
      @endif

      <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">
              アップロードするファイル
            </label>
            <input
              type="file"
              name="file"
              class="mt-1 block w-full text-sm text-gray-500
                      file:mr-4 file:py-2 file:px-4
                      file:rounded-full file:border-0
                      file:text-sm file:font-semibold
                      file:bg-blue-50 file:text-blue-700
                      hover:file:bg-blue-100"
              required>
          </div>
          <div>
            <!-- カテゴリー選択 -->
            <label class="block text
            -sm font-medium text-gray-700">
              カテゴリー
            </label>
            <select
              name="major_category_id"
              class="mt-1 block w-full text-sm text-gray-500 hover:file:bg-blue-100 border-2 rounded"
              required>
              <option value="">選択してください</option>
              @foreach ($majorCategories as $majorCategory)
              <option value="{{ $majorCategory->id }}">{{ $majorCategory->name }}</option>
              @endforeach
            </select>
          </div>

          <livewire:add-minor-category />

          <div>
            <!-- 説明 -->
            <label class="block text
            -sm font-medium text-gray-700">
              説明
            </label>
            <textarea
              name="description"
              class="mt-1 block w-full text-sm text-gray-500 hover:file:bg-blue-100 border-2 rounded"></textarea>
          </div>

          <button
            type="submit"
            class="w-full bg-blue-600 text-white rounded-md py-2 px-4
                  hover:bg-blue-700 focus:outline-none focus:ring-2
                  focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            アップロード
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- 一覧に戻る -->
  <div class="fixed bottom-8 right-8">
    <a href="{{ route('home') }}" class="bg-teal-500 text-white p-2 rounded">一覧に戻る</a>
  </div>
</body>

</html>

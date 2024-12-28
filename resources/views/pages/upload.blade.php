<x-layouts.app>
  <x-sidebar />
  <div class="pl-52 min-h-screen flex items-center justify-center">
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

      <livewire:file-upload-form :majorCategories="$majorCategories" />

    </div>
  </div>

  <!-- 一覧に戻る -->
  <div class="fixed bottom-8 right-8">
    <a href="{{ route('home') }}" class="bg-teal-500 text-white p-2 rounded">一覧に戻る</a>
  </div>
</x-layouts.app>

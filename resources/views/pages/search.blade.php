<x-layouts.app>
  <x-sidebar />
  <div class="pl-52">
    <div class="p-5">
      <h1 class="text-2xl font-bold mb-4">検索結果</h1>
      @if($files->isEmpty())
        <p>該当するファイルが見つかりませんでした。</p>
      @else
        <div class="grid gap-4 grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:flex xl:flex-wrap">
          @foreach ($files as $file)
            <div>
              <img src="{{ $file->thumbnail_link }}" alt="" class="cursor-pointer">
              <p>{{ $file->title }}</p>
            </div>
          @endforeach
        </div>
        {{ $files->links() }}
      @endif
    </div>
  </div>
</x-layouts.app>

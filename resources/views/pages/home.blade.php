<x-layouts.app>
    <x-sidebar />
    <div class="pl-52">
        <div class="p-5">
            <div x-data="{
        open: false,
        title: '',
        image: '',
        description: '',
        upload: '',
        create_at: '',
        major_category: '',
        mime_type: '',
        file_size: '',
        minor_categories: [],
        download_link: '',
        delete_link: ''
        }">
                <!-- 画像リスト -->
                <div class="grid grid-cols-3 gap-4">
                    @foreach ($files as $file)
                    <div>
                        <img src="{{ $file->thumbnail_link }}" alt="" class="cursor-pointer"
                            @click="
                        open = true;
                        title = '{{ $file->title }}';
                        image = '{{ $file->thumbnail_link }}';
                        description = '{{ $file->description }}';
                        upload = '{{ $file->user_name }}';
                        create_at = '{{ $file->created_at }}';
                        major_category = '{{ $file->major_category }}';
                        mime_type = '{{ $file->mime_type }}';
                        file_size = '{{ $file->file_size }}';
                        minor_categories = [
                        @foreach ($file->minor_categories as $minor_category)
                            '{{ $minor_category }}',
                        @endforeach
                        ];
                        download_link = '{{ $file->drive_download_link }}';
                        delete_link = '{{ route('files.delete', $file->id) }}';
                        ">
                    </div>
                    @endforeach
                </div>

                <!-- モーダル -->
                <div x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
                    x-show="open"
                    @click="open = false"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
                    <div class="bg-white p-3 rounded shadow-lg w-8/12 max-w-5xl" @click.stop>
                        <div class="flex justify-end">
                            <button @click="open = false" class="text-red-500">閉じる</button>
                        </div>
                        <div class="max-w-80 m-auto">
                            <h2 class="text-xl mb-4" x-text="title"></h2>
                            <img :src="image" alt="" class="mb-4">
                            <p x-text="`アップロード: ${upload}`"></p>
                            <p x-text="`アップロード日: ${create_at}`"></p>
                            <p x-text="`カテゴリー: ${major_category}`"></p>
                            <p x-text="`ファイル形式: ${mime_type}`"></p>
                            <p x-text="`ファイルサイズ: ${file_size}KB`"></p>
                            <div class="flex flex-wrap">
                                <template x-for="minor_category in minor_categories" :key="minor_category">
                                    <span class="inline-block bg-blue-100 text-blue-700 text-xs rounded-full px-2 py-1 mr-2" x-text="minor_category"></span>
                                </template>
                                <template x-if="minor_categories.length === 0">
                                    <span class="inline-block bg-gray-300 text-black text-xs rounded-full px-2 py-1 mr-2">キーワードなし</span>
                                </template>
                            </div>
                            <div class="flex">
                                <a :href="download_link" class="inline-block bg-teal-500 text-white p-2 rounded">ダウンロード</a>
                                <form :action="delete_link" method="post" onsubmit="return confirm('本当に削除してもよろしいですか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white p-2 rounded">削除</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

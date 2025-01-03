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
        formatted_date: '',
        major_category: '',
        mime_type: '',
        file_size: '',
        minor_categories: [],
        download_link: '',
        delete_link: '',
        delete_button: false,
        }">
                <!-- 画像リスト -->
                <div class="grid gap-4 grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:flex xl:flex-wrap">
                    @foreach ($files as $file)
                    <div>
                        <img src="{{ $file->thumbnail_link }}" alt="" class="cursor-pointer"
                            @click="
                        open = true;
                        title = '{{ $file->title }}';
                        image = '{{ $file->thumbnail_link }}';
                        description = '{{ $file->description }}';
                        upload = '{{ $file->user_name }}';
                        formatted_date = '{{ $file->formatted_date }}';
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
                        delete_button = '{{ $file->is_mine }}';
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
                    <div class="bg-white p-5 rounded shadow-lg w-8/12 max-w-5xl relative" @click.stop>
                        <button @click="open = false" class="text-black-500 text-lg p-1 rounded-md transition absolute top-[3%] right-[3%] leading-none hover:bg-gray-300 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                        <div class="max-w-80 m-auto">
                            <div class="mb-4 flex justify-between items-center gap-1">
                                <h2 class="text-lg break-all" x-text="title"></h2>
                                <p x-text="major_category" class="text-sm font-medium text-green-700 bg-green-100 rounded-md px-2 py-1 flex-shrink-0"></p>
                            </div>
                            <img :src="image" alt="" class="mb-2">
                            <div class="flex flex-wrap">
                                <template x-for="minor_category in minor_categories" :key="minor_category">
                                    <span class="inline-block bg-blue-100 text-blue-700 text-xs rounded-full px-2 py-1 mr-2" x-text="minor_category"></span>
                                </template>
                                <template x-if="minor_categories.length === 0">
                                    <span class="inline-block bg-gray-300 text-black text-xs rounded-full px-2 py-1 mr-2">キーワードなし</span>
                                </template>
                            </div>
                            <div class="flex items-center mt-2 text-sm">
                                <i class="fa-solid fa-user"></i>：<p x-text="upload" class="ml-1"></p>
                            </div>
                            <div class="flex items-center mt-2 text-sm">
                                <i class="fa-solid fa-calendar-days"></i>：<p x-text="formatted_date" class="ml-1"></p>
                            </div>
                            <div class="flex items-center mt-2 text-sm">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-file-image"></i>：<p x-text="mime_type" class="ml-1"></p>
                                    <p x-text="file_size" class="ml-5"></p>
                                </div>
                            </div>
                            <div class="flex justify-between items-center mt-4">
                                <a :href="download_link" class=" bg-teal-500 hover:bg-teal-400 transition text-white p-2 rounded flex items-center gap-1"><i class="fa-solid fa-download text-xl"></i><span class="text-sm">ダウンロード</span></a>
                                <template x-if="delete_button">
                                    <form :action="delete_link" method="post" onsubmit="return confirm('本当に削除してもよろしいですか？');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"><i class="fa-regular fa-trash-can text-2xl text-red-200 hover:text-red-500 transition"></i></button>
                                    </form>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

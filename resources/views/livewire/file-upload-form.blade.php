<div>
    <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return submitForm(this);">
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
                    class="mt-1 block w-full text-sm text-gray-500 hover:file:bg-blue-100 border-2 rounded"
                    onkeydown="if(event.keyCode==13 && !event.shiftKey){event.preventDefault();return false;}"></textarea>
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

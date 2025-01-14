<div>
    <form action="{{ route('files.update', $file) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <img src="{{ $file->thumbnail_link }}" alt="">
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
                    wire:model.live="selectedCategory"
                    required>
                    <option value=""class="bg-gray-400 text-white" disabled>選択してください</option>
                    @foreach ($majorCategories as $majorCategory)
                    <option value="{{ $majorCategory->id }}">{{ $majorCategory->name }}</option>
                    @endforeach
                </select>
            </div>

            <livewire:add-minor-category  :havingMinorCategories="$file->minor_categories" />

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

            <!-- プログレスバー -->
            <div id="progress-container" class="hidden">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progress-bar" class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                </div>
                <p id="progress-text" class="text-sm text-gray-500 mt-1 text-center">0%</p>
            </div>

            <button
                type="submit"
                id="submit-button"
                class="w-full rounded-md py-2 px-4 transition-colors bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                更新
            </button>
        </div>
    </form>

<div>
    <label class="block text-sm font-medium text-gray-700">
        キーワード
    </label>
    <div class="flex">
        <input
            type="text"
            wire:model="minorCategory"
            class="block w-full text-sm text-gray-500 border-2 rounded">
        <button
            type="button"
            wire:click.prevent="toArrayMinorCategory"
            class="w-2/12 bg-blue-600 text-white text-xs rounded-md px-1
                            hover:bg-blue-700 focus:outline-none focus:ring-2
                            focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            追加
        </button>
    </div>
    <div>
        @foreach ($minorCategories as $index => $minorCategory)
        <span class="inline-block bg-blue-100 text-blue-700 text-xs rounded-full px-2 py-1 mr-2">
            {{ $minorCategory }}
            <button
                wire:click.prevent="removeMinorCategory('{{ $index }}')"
                class="text-red-700 text-xs ml-1">
                ×
            </button>
        </span>
        <input type="hidden" name="minorCategories[]" value="{{ $minorCategory }}">
        @endforeach
    </div>
</div>

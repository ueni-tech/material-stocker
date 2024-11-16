<div>
    <label class="block text-sm font-medium text-gray-700">
        キーワード
    </label>
    <div>
        @foreach($sujestMinorCategories as $sujestMinorCategory)
        <span
            wire:click.prevent="toArrayMinorCategory('{{ $sujestMinorCategory->name }}')"
            class="inline-block bg-gray-200 text-black text-xs rounded-full px-2 py-1 mr-2 cursor-pointer"
            wire:key="{{ $sujestMinorCategory->id }}">
            {{ $sujestMinorCategory->name }}
        </span>
        @endforeach
    </div>
    <div class="flex">
        <input
            type="text"
            wire:model.live.debounce.300ms="minorCategory"
            wire:keydown.enter="toArrayMinorCategory"
            class="block w-full text-sm text-gray-500 border-2 rounded">
        @if($isActive)
        <button
            type="button"
            wire:click.prevent="toArrayMinorCategory"
            class="w-2/12 bg-blue-600 text-white text-xs rounded-md px-1
                                hover:bg-blue-700 focus:outline-none focus:ring-2
                                focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            追加
        </button>
        @else
        <button
            type="button"
            class="w-2/12 bg-gray-300 text-gray-500 text-xs rounded-md px-1"
            disabled>
            追加
        </button>
        @endif
    </div>
    <div>
        @foreach ($minorCategories as $index => $minorCategory)
        <span class="inline-block bg-blue-100 text-blue-700 text-xs rounded-full px-2 py-1 mr-2"
            wire:key="{{ $index }}">
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

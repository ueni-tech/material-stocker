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
                    wire:click="resetFile"
                    wire:model.live="file"
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
                    wire:model.live="selectedCategory"
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
                class="w-full rounded-md py-2 px-4 transition-colors {{ $isFormValid 
                ? 'bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2'
                : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
                {{ !$isFormValid ? 'disabled' : '' }}>
                アップロード
            </button>
        </div>
    </form>

    <script>
        function submitForm(form) {
            // プログレス表示の要素を取得
            const progressContainer = document.getElementById('progress-container');
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            const submitButton = document.getElementById('submit-button');

            // プログレスバーを表示
            progressContainer.classList.remove('hidden');
            submitButton.disabled = true;

            // FormDataオブジェクトの作成
            const formData = new FormData(form);

            // XMLHttpRequestの設定
            const xhr = new XMLHttpRequest();
            xhr.open(form.method, form.action);

            // プログレスイベントの監視
            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percentComplete + '%';
                    progressText.textContent = percentComplete + '%';
                }
            };

            // 完了時の処理
            xhr.onload = function() {
                // 通常のフォーム送信を実行
                form.submit();
            };

            // エラー時の処理
            xhr.onerror = function() {
                progressContainer.classList.add('hidden');
                submitButton.disabled = false;
                alert('エラーが発生しました');
            };

            // リクエストの送信
            xhr.send(formData);

            // デフォルトのフォーム送信を防ぐ
            return false;
        }
    </script>
</div>

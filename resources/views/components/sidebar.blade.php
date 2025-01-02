<aside class="w-52 h-screen bg-white shadow-lg fixed left-0 top-0">
    <div class="p-4">
        <h2 class="text-lg font-medium mb-4">メニュー</h2>
        <nav class="space-y-2">
            <x-nav-link
                :route="'home'"
                :active="Route::is('home')">
                アップロード済み画像
            </x-nav-link>
            <x-nav-link
                :route="'files.upload'"
                :active="Route::is('files.upload')">
                アップロード
            </x-nav-link>
            <a href="{{ route('logout') }}" class="js-logout block px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded">
                ログアウト
            </a>
        </nav>
    </div>
</aside>

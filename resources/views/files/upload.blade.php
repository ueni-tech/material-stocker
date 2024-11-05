<!-- resources/views/files/upload.blade.php -->
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ファイルアップロード</title>
  <!-- TailwindCSSを使用 -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- axiosを使用 -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body class="bg-gray-100">
  <div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
      <h1 class="text-2xl font-bold text-center mb-8">ファイルアップロード</h1>

      <!-- ファイルアップロードフォーム -->
      <form id="uploadForm" class="space-y-6">
        <div class="space-y-2">
          <label class="block text-sm font-medium text-gray-700">
            アップロードするファイル
          </label>
          <input
            type="file"
            name="file"
            id="file"
            class="block w-full text-sm text-gray-500
                               file:mr-4 file:py-2 file:px-4
                               file:rounded-full file:border-0
                               file:text-sm file:font-semibold
                               file:bg-blue-50 file:text-blue-700
                               hover:file:bg-blue-100"
            required>
        </div>

        <!-- プログレスバー -->
        <div id="progressContainer" class="hidden">
          <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div id="progressBar"
              class="bg-blue-600 h-2.5 rounded-full"
              style="width: 0%">
            </div>
          </div>
          <p id="progressText" class="text-sm text-gray-600 mt-1 text-center">0%</p>
        </div>

        <button
          type="submit"
          id="submitButton"
          class="w-full bg-blue-600 text-white rounded-md py-2 px-4
                           hover:bg-blue-700 focus:outline-none focus:ring-2
                           focus:ring-blue-500 focus:ring-offset-2 transition-colors">
          アップロード
        </button>
      </form>

      <!-- 結果表示エリア -->
      <div id="result" class="mt-6 hidden">
        <div id="successMessage" class="hidden">
          <div class="bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm text-green-700">
                  アップロードが完了しました
                </p>
                <a id="fileLink" href="#" target="_blank" class="text-sm text-green-700 hover:text-green-600 underline">
                  ファイルを表示
                </a>
              </div>
            </div>
          </div>
        </div>

        <div id="errorMessage" class="hidden">
          <div class="bg-red-50 border-l-4 border-red-400 p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm text-red-700" id="errorText"></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('uploadForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      const fileInput = document.getElementById('file');
      const submitButton = document.getElementById('submitButton');
      const progressContainer = document.getElementById('progressContainer');
      const progressBar = document.getElementById('progressBar');
      const progressText = document.getElementById('progressText');
      const result = document.getElementById('result');
      const successMessage = document.getElementById('successMessage');
      const errorMessage = document.getElementById('errorMessage');
      const errorText = document.getElementById('errorText');
      const fileLink = document.getElementById('fileLink');

      if (!fileInput.files.length) {
        alert('ファイルを選択してください。');
        return;
      }

      const formData = new FormData();
      formData.append('file', fileInput.files[0]);

      try {
        // UIの初期化
        submitButton.disabled = true;
        progressContainer.classList.remove('hidden');
        result.classList.add('hidden');
        successMessage.classList.add('hidden');
        errorMessage.classList.add('hidden');

        const response = await axios.post('/upload', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          },
          onUploadProgress: (progressEvent) => {
            const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
            progressBar.style.width = percentCompleted + '%';
            progressText.textContent = percentCompleted + '%';
          }
        });

        if (response.data.success) {
          result.classList.remove('hidden');
          successMessage.classList.remove('hidden');
          if (response.data.data.view_link) {
            fileLink.href = response.data.data.view_link;
          } else {
            fileLink.classList.add('hidden');
          }
          fileInput.value = '';
        }

      } catch (error) {
        result.classList.remove('hidden');
        errorMessage.classList.remove('hidden');
        errorText.textContent = error.response?.data?.message || 'アップロードに失敗しました。';
      } finally {
        submitButton.disabled = false;
        progressContainer.classList.add('hidden');
      }
    });
  </script>
</body>

</html>

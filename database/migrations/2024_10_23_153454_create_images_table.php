<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('images', function (Blueprint $table) {
      $table->id();
      $table->foreignId('major_category_id')->constrained()->cascadeOnDelete();
      // Google Drive関連
      $table->string('drive_file_id');      // GoogleドライブのファイルID
      $table->string('drive_view_link');    // 閲覧用リンク
      $table->string('drive_download_link'); // ダウンロード用リンク

      // ファイル情報
      $table->string('title');
      $table->string('user_name');
      $table->string('mime_type');          // ファイルのMIMEタイプ
      $table->bigInteger('file_size');      // ファイルサイズ（バイト）
      $table->string('discription')->nullable(); // 説明
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('images');
  }
};

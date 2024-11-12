<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class imageUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 画像ファイルのバリデーションルール
            'file' => 'required|file|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
            'major_category_id' => 'required|integer|exists:major_categories,id',
            'minor_categorys.*' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'file.required' => 'ファイルは必須です。',
            'file.image' => 'ファイルは画像でなければなりません。',
            'file.mimes' => 'ファイル形式は jpeg, png, jpg, gif, webp, svg のいずれかでなければなりません。',
            'file.max' => 'ファイルサイズは10MB以下でなければなりません。',
            'major_category_id.required' => 'カテゴリは必須です。',
            'major_category_id.exists' => '選択されたカテゴリは存在しません。',
            'minor_categorys.*.max' => 'キーワードは255文字以下でなければなりません。',
            'description.max' => '説明は255文字以下でなければなりません。',
        ];
    }
}

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
        ];
    }
}

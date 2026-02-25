<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Support\Sanitizer;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'author_name'  => ['required', 'string', 'max:255'],
            'subtitle'     => ['nullable', 'string', 'max:255'],
            'excerpt'      => ['nullable', 'string', 'max:2000'],
            'body'         => ['required', 'string'],
            'image'        => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'mimetypes:image/jpeg,image/png,image/webp', 'max:4096', 'dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000'],
            'image_2'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'mimetypes:image/jpeg,image/png,image/webp', 'max:4096', 'dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000'],
            'image_3'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'mimetypes:image/jpeg,image/png,image/webp', 'max:4096', 'dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000'],
            'image_4'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'mimetypes:image/jpeg,image/png,image/webp', 'max:4096', 'dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000'],
            'video_url'    => ['nullable', 'url'],
            'category_id'  => ['nullable', 'exists:categories,id'],
            'is_featured'  => ['sometimes', 'boolean'],
            'is_popular'   => ['sometimes', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title'       => Sanitizer::plain($this->input('title'), 255),
            'author_name' => Sanitizer::plain($this->input('author_name'), 255),
            'subtitle'    => Sanitizer::plain($this->input('subtitle'), 255),
            'excerpt'     => Sanitizer::plain($this->input('excerpt'), 2000),
            'body'        => Sanitizer::rich($this->input('body')),
            'video_url'   => Sanitizer::plain($this->input('video_url'), 2000),
        ]);
    }
}

<?php

namespace App\Services;

use App\Models\EducationalContent;
use App\Services\Support\MediaStorage;
use App\Services\Support\Sanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EducationalContentService extends BaseService
{
    public function __construct(private MediaStorage $media) {}

    public function create(array $validated, Request $request): EducationalContent
    {
        $data = $this->sanitize($validated);
        $data['slug'] = Str::slug($data['title']).'-'.time();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_premium'] = $request->boolean('is_premium');
        $data['published_at'] = now();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->media->storePublic($request->file('cover_image'), 'educational');
        }

        return EducationalContent::create($data);
    }

    public function update(EducationalContent $content, array $validated, Request $request): EducationalContent
    {
        $data = $this->sanitize($validated);
        if ($content->title !== ($data['title'] ?? $content->title)) {
            $data['slug'] = Str::slug($data['title']).'-'.time();
        }

        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_premium'] = $request->boolean('is_premium');

        if ($request->hasFile('cover_image')) {
            $this->media->deletePublic($content->cover_image);
            $data['cover_image'] = $this->media->storePublic($request->file('cover_image'), 'educational');
        }

        $content->update($data);
        return $content;
    }

    public function delete(EducationalContent $content): void
    {
        $this->guard(function () use ($content) {
        $this->media->deletePublic($content->cover_image);
        $content->delete();
    
            return null;
        }, __METHOD__);
}

    private function sanitize(array $data): array
    {
        $data['title'] = Sanitizer::plain($data['title'] ?? null, 255);
        $data['level'] = Sanitizer::plain($data['level'] ?? null, 50);
        $data['topic'] = Sanitizer::plain($data['topic'] ?? null, 255);
        $data['excerpt'] = Sanitizer::plain($data['excerpt'] ?? null, 2000);
        $data['body'] = Sanitizer::rich($data['body'] ?? null, 500000);
        return $data;
    }
}

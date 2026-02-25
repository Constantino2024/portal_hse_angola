<?php

namespace App\Services;

use App\Models\EsgInitiative;
use App\Services\Support\MediaStorage;
use App\Services\Support\Sanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EsgInitiativeService extends BaseService
{
    public function __construct(private MediaStorage $media) {}

    public function create(array $validated, Request $request, int $userId): EsgInitiative
    {
        $data = $this->sanitize($validated);
        $data['user_id'] = $userId;
        $data['slug'] = Str::slug($data['title']).'-'.time();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['published_at'] = now();
        if ($request->hasFile('image')) {
            $data['image_path'] = $this->media->storePublic($request->file('image'), 'esg/initiatives');
        }
        return EsgInitiative::create($data);
    }

    public function update(EsgInitiative $initiative, array $validated, Request $request): EsgInitiative
    {
        $data = $this->sanitize($validated);
        if ($initiative->title !== ($data['title'] ?? $initiative->title)) {
            $data['slug'] = Str::slug($data['title']).'-'.time();
        }
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $this->media->deletePublic($initiative->image_path);
            $data['image_path'] = $this->media->storePublic($request->file('image'), 'esg/initiatives');
        }
        $initiative->update($data);
        return $initiative;
    }

    public function delete(EsgInitiative $initiative): void
    {
        $this->guard(function () use ($initiative) {
        $this->media->deletePublic($initiative->image_path);
        $initiative->delete();
    
            return null;
        }, __METHOD__);
}

    private function sanitize(array $data): array
    {
        $data['title'] = Sanitizer::plain($data['title'] ?? null, 255);
        $data['focus_area'] = Sanitizer::plain($data['focus_area'] ?? null, 255);
        $data['location'] = Sanitizer::plain($data['location'] ?? null, 255);
        $data['excerpt'] = Sanitizer::plain($data['excerpt'] ?? null, 2000);
        $data['description'] = Sanitizer::rich($data['description'] ?? null, 500000);
        return $data;
    }
}

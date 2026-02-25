<?php

namespace App\Services;

use App\Models\AgendaItem;
use App\Services\Support\MediaStorage;
use App\Services\Support\Sanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgendaService extends BaseService
{
    public function __construct(private MediaStorage $media) {}

    public function create(array $validated, Request $request): AgendaItem
    {
        return $this->guard(function () use ($validated, $request) {
        $data = $this->sanitize($validated);

        $data['slug'] = Str::slug($data['title']).'-'.time();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['registration_enabled'] = $request->boolean('registration_enabled', true);
        $data['is_online'] = $request->boolean('is_online');
        $data['published_at'] = now();

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->media->storePublic($request->file('image'), 'agenda');
        }

        return AgendaItem::create($data);
    
        }, __METHOD__);
}

    public function update(AgendaItem $item, array $validated, Request $request): AgendaItem
    {
        return $this->guard(function () use ($item, $validated, $request) {
        $data = $this->sanitize($validated);

        if ($item->title !== ($data['title'] ?? $item->title)) {
            $data['slug'] = Str::slug($data['title']).'-'.time();
        }

        $data['is_active'] = $request->boolean('is_active', true);
        $data['registration_enabled'] = $request->boolean('registration_enabled', true);
        $data['is_online'] = $request->boolean('is_online');

        if ($request->hasFile('image')) {
            $this->media->deletePublic($item->image_path);
            $data['image_path'] = $this->media->storePublic($request->file('image'), 'agenda');
        }

        $item->update($data);
        return $item;
    
        }, __METHOD__);
}

    public function delete(AgendaItem $item): void
    {
        $this->guard(function () use ($item) {
        $this->media->deletePublic($item->image_path);
        $item->delete();
    
            return null;
        }, __METHOD__);
}

    private function sanitize(array $data): array
    {
        $data['title'] = Sanitizer::plain($data['title'] ?? null, 255);
        $data['type'] = Sanitizer::key($data['type'] ?? null, 40);
        $data['excerpt'] = Sanitizer::plain($data['excerpt'] ?? null, 2000);
        $data['description'] = Sanitizer::plain($data['description'] ?? null, 20000);
        $data['location'] = Sanitizer::plain($data['location'] ?? null, 255);
        $data['external_registration_url'] = isset($data['external_registration_url']) ? trim($data['external_registration_url']) : null;
        return $data;
    }
}

<?php

namespace App\Services;

use App\Models\CompanyProject;
use App\Services\Support\MediaStorage;
use App\Services\Support\Sanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyProjectService extends BaseService
{
    public function __construct(private MediaStorage $media) {}

    public function create(array $validated, Request $request, int $userId): CompanyProject
    {
        $data = $this->sanitize($validated);
        $data['user_id'] = $userId;
        $data['slug'] = Str::slug($data['title']).'-'.time();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['published_at'] = now();
        if ($request->hasFile('image')) {
            $data['image_path'] = $this->media->storePublic($request->file('image'), 'esg/projects');
        }
        return CompanyProject::create($data);
    }

    public function update(CompanyProject $project, array $validated, Request $request): CompanyProject
    {
        $data = $this->sanitize($validated);
        if ($project->title !== ($data['title'] ?? $project->title)) {
            $data['slug'] = Str::slug($data['title']).'-'.time();
        }
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $this->media->deletePublic($project->image_path);
            $data['image_path'] = $this->media->storePublic($request->file('image'), 'esg/projects');
        }

        $project->update($data);
        return $project;
    }

    public function delete(CompanyProject $project): void
    {
        $this->guard(function () use ($project) {
        $this->media->deletePublic($project->image_path);
        $project->delete();
    
            return null;
        }, __METHOD__);
}

    private function sanitize(array $data): array
    {
        $data['title'] = Sanitizer::plain($data['title'] ?? null, 255);
        $data['client'] = Sanitizer::plain($data['client'] ?? null, 255);
        $data['sector'] = Sanitizer::plain($data['sector'] ?? null, 255);
        $data['location'] = Sanitizer::plain($data['location'] ?? null, 255);
        $data['excerpt'] = Sanitizer::plain($data['excerpt'] ?? null, 2000);
        $data['description'] = Sanitizer::rich($data['description'] ?? null, 500000);
        $data['website'] = isset($data['website']) ? trim($data['website']) : null;
        return $data;
    }
}

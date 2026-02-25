<?php

namespace App\Services;

use App\Models\Trabalho;
use App\Services\Support\Sanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobService extends BaseService
{
    public function create(array $validated, Request $request): Trabalho
    {
        $data = $this->sanitize($validated);
        $data['slug'] = Str::slug($data['title']).'-'.time();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_sponsored'] = $request->boolean('is_sponsored');
        $data['published_at'] = now();

        return Trabalho::create($data);
    }

    public function update(Trabalho $job, array $validated, Request $request): Trabalho
    {
        $data = $this->sanitize($validated);
        if ($job->title !== ($data['title'] ?? $job->title)) {
            $data['slug'] = Str::slug($data['title']).'-'.time();
        }
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_sponsored'] = $request->boolean('is_sponsored');

        $job->update($data);
        return $job;
    }

    public function delete(Trabalho $job): void
    {
        $this->guard(function () use ($job) {
        $job->delete();
    
            return null;
        }, __METHOD__);
}

    private function sanitize(array $data): array
    {
        $data['title'] = Sanitizer::plain($data['title'] ?? null, 255);
        $data['company'] = Sanitizer::plain($data['company'] ?? null, 255);
        $data['location'] = Sanitizer::plain($data['location'] ?? null, 255);
        $data['type'] = Sanitizer::plain($data['type'] ?? null, 255);
        $data['level'] = Sanitizer::plain($data['level'] ?? null, 255);
        $data['excerpt'] = Sanitizer::plain($data['excerpt'] ?? null, 2000);
        $data['description'] = Sanitizer::rich($data['description'] ?? null, 20000);
        $data['requirements'] = Sanitizer::plain($data['requirements'] ?? null, 20000);
        $data['apply_link'] = isset($data['apply_link']) ? trim($data['apply_link']) : null;
        $data['apply_email'] = isset($data['apply_email']) ? strtolower(trim($data['apply_email'])) : null;
        return $data;
    }
}

<?php

namespace App\Services;

use App\Mail\NewPostPublished;
use App\Models\Post;
use App\Models\Subscriber;
use App\Models\Tag;
use App\Services\Support\MediaStorage;
use App\Services\Support\Sanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PostService extends BaseService
{
    public function __construct(private MediaStorage $media) {}

    public function create(array $validated, Request $request): Post
    {
        $data = $this->sanitize($validated);
        $data['slug'] = Str::slug($data['title']).'-'.time();
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_popular']  = $request->boolean('is_popular');
        $data['published_at'] = $validated['published_at'] ?? now();

        $this->handleImages($data, $request, null);

        $post = Post::create($data);
        $this->syncTags($post, $request->input('tags'));

        // como a criação publica (por padrão), notifica
        if ($post->published_at) {
            $this->notifySubscribers($post);
        }

        return $post;
    }

    public function update(Post $post, array $validated, Request $request): Post
    {
        $data = $this->sanitize($validated);
        if ($post->title !== ($data['title'] ?? $post->title)) {
            $data['slug'] = Str::slug($data['title']).'-'.time();
        }
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_popular']  = $request->boolean('is_popular');

        $wasUnpublished = $post->published_at === null;

        $this->handleImages($data, $request, $post);

        $post->update($data);
        $this->syncTags($post, $request->input('tags'));

        if ($wasUnpublished && $post->published_at) {
            $this->notifySubscribers($post);
        }

        return $post;
    }

    public function delete(Post $post): void
    {
        $this->guard(function () use ($post) {
        // apaga imagens associadas antes
        $this->media->deletePublic($post->image_url);
        $this->media->deletePublic($post->image_2_url);
        $this->media->deletePublic($post->image_3_url);
        $this->media->deletePublic($post->image_4_url);

        $post->delete();
    
            return null;
        }, __METHOD__);
}

    private function sanitize(array $data): array
    {
        $data['title']       = Sanitizer::plain($data['title'] ?? null, 255);
        $data['author_name'] = Sanitizer::plain($data['author_name'] ?? null, 255);
        $data['subtitle']    = Sanitizer::plain($data['subtitle'] ?? null, 255);
        $data['excerpt']     = Sanitizer::plain($data['excerpt'] ?? null, 2000);
        $data['body']        = Sanitizer::rich($data['body'] ?? null, 500000);
        $data['video_url']   = isset($data['video_url']) ? trim($data['video_url']) : null;
        return $data;
    }

    private function handleImages(array &$data, Request $request, ?Post $existing): void
    {
        $map = [
            'image'   => 'image_url',
            'image_2' => 'image_2_url',
            'image_3' => 'image_3_url',
            'image_4' => 'image_4_url',
        ];

        foreach ($map as $input => $column) {
            if (!$request->hasFile($input)) continue;
            if ($existing) {
                $this->media->deletePublic($existing->{$column} ?? null);
            }
            $data[$column] = $this->media->storePublic($request->file($input), 'posts');
        }
    }

    private function notifySubscribers(Post $post): void
    {
        $subscribers = Subscriber::where('is_active', true)->pluck('email')->all();
        if (empty($subscribers)) {
            \Log::info("Nenhum inscrito para enviar email.");
            return;
        }

        foreach ($subscribers as $email) {
            Mail::to($email)->queue(new NewPostPublished($post));
        }

        \Log::info("Fila criada para enviar email da notícia ID {$post->id}");
    }

    private function syncTags(Post $post, ?string $tagsInput): void
    {
        if (!$tagsInput) {
            $post->tags()->detach();
            return;
        }

        $names = collect(explode(',', $tagsInput))
            ->map(fn($name) => trim($name))
            ->filter()
            ->unique();

        $tagIds = [];
        foreach ($names as $name) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
            $tagIds[] = $tag->id;
        }

        $post->tags()->sync($tagIds);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminAgendaValidationTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Workshop de HSE',
            'type' => 'workshop',
            'excerpt' => 'Resumo',
            'description' => 'Detalhes do evento',
            'starts_at' => now()->addDay()->toDateTimeString(),
            'ends_at' => now()->addDays(2)->toDateTimeString(),
            'location' => 'Luanda',
            'capacity' => 50,
            'external_registration_url' => 'https://example.com/inscricao',
            'is_active' => 1,
            'registration_enabled' => 1,
            'is_online' => 0,
        ], $overrides);
    }

    public function test_store_requires_image(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)
            ->post(route('admin.agenda.store'), $this->validPayload())
            ->assertSessionHasErrors(['image']);
    }

    public function test_store_rejects_non_image_mime(): void
    {
        $editor = User::factory()->editor()->create();

        $payload = $this->validPayload([
            'image' => UploadedFile::fake()->create('doc.pdf', 10, 'application/pdf'),
        ]);

        $this->actingAs($editor)
            ->post(route('admin.agenda.store'), $payload)
            ->assertSessionHasErrors(['image']);
    }

    public function test_store_rejects_too_small_image_dimensions(): void
    {
        $editor = User::factory()->editor()->create();

        $payload = $this->validPayload([
            'image' => UploadedFile::fake()->image('small.jpg', 100, 100),
        ]);

        $this->actingAs($editor)
            ->post(route('admin.agenda.store'), $payload)
            ->assertSessionHasErrors(['image']);
    }
}

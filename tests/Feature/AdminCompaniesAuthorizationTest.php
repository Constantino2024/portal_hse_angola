<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCompaniesAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_editor_cannot_access_companies_management(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)
            ->get(route('admin.companies.index'))
            ->assertStatus(403);
    }

    public function test_admin_can_access_companies_management(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.companies.index'))
            ->assertOk();
    }
}

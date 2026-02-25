<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Utilizadores de demonstração (password: password)
        User::firstOrCreate(
            ['email' => 'admin@portalhse.local'],
            ['name' => 'Administrador', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        User::firstOrCreate(
            ['email' => 'empresa@portalhse.local'],
            ['name' => 'Empresa Demo', 'password' => Hash::make('password'), 'role' => 'empresa']
        );

        User::firstOrCreate(
            ['email' => 'profissional@portalhse.local'],
            ['name' => 'Profissional Demo', 'password' => Hash::make('password'), 'role' => 'profissional']
        );

        $this->call(DemoContentSeeder::class);
    }
}

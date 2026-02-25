<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agenda_items', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();

            // Tipos: eventos, workshops, formações, datas internacionais, webinars
            $table->string('type')->index();

            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();

            $table->dateTime('starts_at')->index();
            $table->dateTime('ends_at')->nullable()->index();

            $table->string('location')->nullable();
            $table->boolean('is_online')->default(false);

            // Inscrição directa
            $table->boolean('registration_enabled')->default(true);
            $table->unsignedInteger('capacity')->nullable(); // null = ilimitado
            $table->string('external_registration_url')->nullable(); // caso use link externo

            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable()->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_items');
    }
};

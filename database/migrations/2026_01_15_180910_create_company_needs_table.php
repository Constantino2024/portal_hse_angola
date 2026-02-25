<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_needs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // empresa

            $table->string('title');
            // IMPORTANT: usando ENUM + tamanhos curtos para evitar erro MySQL
            // "Specified key was too long" em índices compostos com utf8mb4.
            $table->enum('level', ['junior', 'pleno', 'senior']);
            $table->enum('area', ['hst', 'ambiente', 'esg', 'medico_trabalho', 'qhse']);
            $table->enum('availability', ['obra', 'projecto', 'permanente']);
            $table->string('province', 60);

            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'level', 'area', 'availability', 'province']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_needs');
    }
};

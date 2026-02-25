<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trabalhos', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();

            $table->string('company')->nullable();
            $table->string('location')->nullable();
            $table->string('type')->nullable(); // Tempo integral, Contrato, Estágio...
            $table->string('level')->nullable(); // Júnior, Pleno, Sénior (opcional)

            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->longText('requirements')->nullable();

            $table->string('apply_link')->nullable();   // link para candidatura
            $table->string('apply_email')->nullable();  // email para candidatura (opcional)

            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);   // destaque na home
            $table->boolean('is_sponsored')->default(false);  // vaga patrocinada (monetização)

            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabalhos');
    }
};

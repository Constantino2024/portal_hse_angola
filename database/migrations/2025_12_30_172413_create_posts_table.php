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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();      // texto pequeno abaixo do título
            $table->text('excerpt')->nullable();         // resumo
            $table->string('author_name')->nullable();
            $table->text('body');                    // conteúdo completo
            $table->string('image_url')->nullable();     // imagem de destaque
            $table->string('video_url')->nullable();     // link youtube/vimeo (opcional)
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_featured')->default(false);  // destaque no topo
            $table->boolean('is_popular')->default(false);   // aparece na secção popular
            $table->timestamp('published_at')->nullable();   // data de publicação
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

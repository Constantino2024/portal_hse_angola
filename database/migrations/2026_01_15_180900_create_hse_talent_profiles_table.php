<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hse_talent_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            $table->enum('level', ['junior', 'pleno', 'senior']);
            $table->enum('area', ['tecnico_trabalho', 'ambientalistas', 'psicologos', 'medico_trabalho', 'higienistas']);
            $table->enum('availability', ['obra', 'projecto', 'permanente']);
            $table->string('province', 60);

            // Conteúdo
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();

            // CV (upload)
            $table->string('cv_path')->nullable();

            // Para futuro (ex.: tornar premium)
            $table->boolean('is_public')->default(true);

             // Informações pessoais
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('nationality')->nullable();
            $table->string('marital_status')->nullable();
            $table->text('address')->nullable();
            
            // Educação e formação
            $table->json('education')->nullable(); // Array de formações
            $table->json('certifications')->nullable(); // Array de certificações
            $table->json('languages')->nullable(); // Array de idiomas
            
            // Experiência profissional
            $table->json('work_experience')->nullable(); // Array de experiências
            
            // Habilidades
            $table->json('skills')->nullable(); // Array de habilidades
            
            // Imagem de perfil
            $table->string('profile_image')->nullable();
            
            // Outras informações
            $table->string('current_position')->nullable();
            $table->integer('years_experience')->nullable();
            $table->decimal('expected_salary', 10, 2)->nullable();
            $table->string('preferred_location')->nullable();
            $table->string('drivers_license')->nullable();
            
            // Campos para match
            $table->json('preferred_areas')->nullable();
            $table->json('preferred_company_types')->nullable();

            $table->timestamps();

            $table->index(['level', 'area', 'availability', 'province']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hse_talent_profiles');
    }
};

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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Informações básicas
            $table->string('company_name');
            $table->string('trading_name')->nullable();
            $table->string('nif', 25)->unique();
            $table->string('sector');
            $table->string('company_size')->nullable(); // Pequena, Média, Grande
            $table->year('foundation_year')->nullable();
            
            // Contato
            $table->string('phone', 20);
            $table->string('email')->unique();
            $table->string('website')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_position')->nullable();
            
            // Endereço
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->string('country')->default('Angola');
            
            // Logo e imagens
            $table->string('logo_path')->nullable();
            $table->string('banner_path')->nullable();
            
            // Sobre a empresa
            $table->text('description')->nullable();
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();
            $table->text('values')->nullable();
            
            // Redes sociais
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            
            // HSE/ESG
            $table->boolean('has_hse_department')->default(false);
            $table->boolean('has_esg_policy')->default(false);
            $table->string('hse_manager_name')->nullable();
            $table->string('hse_manager_contact')->nullable();
            
            // Status
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_active')->default(true);
            
            // Métricas
            $table->integer('total_jobs_posted')->default(0);
            $table->integer('total_views')->default(0);
            $table->integer('total_applications')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index(['is_verified', 'is_active']);
            $table->index('sector');
            $table->index('province');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

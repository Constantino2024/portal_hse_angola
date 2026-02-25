<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agenda_registrations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('agenda_item_id')->constrained('agenda_items')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('email')->index();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();

            $table->text('notes')->nullable();
            $table->timestamp('registered_at')->useCurrent();

            // Evita duplicados por email no mesmo item
            $table->unique(['agenda_item_id', 'email']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_registrations');
    }
};

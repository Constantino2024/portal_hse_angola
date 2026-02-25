<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Paginação eficiente por id
            $table->index(['conversation_id', 'id'], 'messages_conversation_id_id_index');
        });

        Schema::table('conversation_user', function (Blueprint $table) {
            // Consultas rápidas "todas conversas do utilizador"
            $table->index(['user_id', 'conversation_id'], 'conversation_user_user_conversation_index');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('messages_conversation_id_id_index');
        });

        Schema::table('conversation_user', function (Blueprint $table) {
            $table->dropIndex('conversation_user_user_conversation_index');
        });
    }
};

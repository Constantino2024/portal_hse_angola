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
       Schema::table('conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('conversations', 'last_message_id')) {
                $table->foreignId('last_message_id')->nullable()->after('last_message_at')
                    ->constrained('messages')->nullOnDelete();
            }
        });

        Schema::table('conversation_user', function (Blueprint $table) {
            if (!Schema::hasColumn('conversation_user', 'last_read_message_id')) {
                $table->foreignId('last_read_message_id')->nullable()->after('user_id')
                    ->constrained('messages')->nullOnDelete();
                $table->timestamp('last_read_at')->nullable()->after('last_read_message_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('conversation_user', function (Blueprint $table) {
            $table->dropColumn(['last_read_message_id', 'last_read_at']);
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign(['last_message_id']);
            $table->dropColumn('last_message_id');
        });
    }

};

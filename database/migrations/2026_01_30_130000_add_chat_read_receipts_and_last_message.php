<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('conversations', 'last_message_id')) {
                $table->unsignedBigInteger('last_message_id')->nullable()->after('last_message_at');
                $table->index('last_message_id');
            }
        });

        Schema::table('conversation_user', function (Blueprint $table) {
            if (!Schema::hasColumn('conversation_user', 'last_read_message_id')) {
                $table->unsignedBigInteger('last_read_message_id')->nullable()->after('user_id');
                $table->timestamp('last_read_at')->nullable()->after('last_read_message_id');
                $table->index(['user_id', 'last_read_message_id'], 'conversation_user_read_index');
            }
        });

        // FK for last_message_id -> messages.id (safe-guarded)
        Schema::table('conversations', function (Blueprint $table) {
            try {
                $table->foreign('last_message_id')->references('id')->on('messages')->nullOnDelete();
            } catch (Throwable $e) {
                // ignore if already exists
            }
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            try { $table->dropForeign(['last_message_id']); } catch (Throwable $e) {}
            if (Schema::hasColumn('conversations', 'last_message_id')) {
                try { $table->dropIndex(['last_message_id']); } catch (Throwable $e) {}
                $table->dropColumn('last_message_id');
            }
        });

        Schema::table('conversation_user', function (Blueprint $table) {
            try { $table->dropIndex('conversation_user_read_index'); } catch (Throwable $e) {}
            if (Schema::hasColumn('conversation_user', 'last_read_message_id')) {
                $cols = [];
                if (Schema::hasColumn('conversation_user', 'last_read_at')) $cols[]='last_read_at';
                $cols[]='last_read_message_id';
                $table->dropColumn($cols);
            }
        });
    }
};

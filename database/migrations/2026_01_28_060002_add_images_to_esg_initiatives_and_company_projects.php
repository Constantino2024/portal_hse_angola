<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('esg_initiatives', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('description');
        });

        Schema::table('company_projects', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('esg_initiatives', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('company_projects', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};

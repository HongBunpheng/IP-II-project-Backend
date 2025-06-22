<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Replace 'authens' with your real table name if different
        if (Schema::hasTable('authens')) {
            Schema::table('authens', function (Blueprint $table) {
                if (!Schema::hasColumn('authens', 'verification_code')) {
                    $table->string('verification_code')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('authens') && Schema::hasColumn('authens', 'verification_code')) {
            Schema::table('authens', function (Blueprint $table) {
                $table->dropColumn('verification_code');
            });
        }
    }
};

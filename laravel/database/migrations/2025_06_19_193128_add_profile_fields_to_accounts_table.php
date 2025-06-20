<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->string('address')->nullable();
            $table->string('profile_picture')->nullable();
            $table->json('featured_picture')->nullable();
            $table->json('social_links')->nullable();
            $table->text('bio')->nullable();
            $table->string('nickname')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            //
        });
    }
};

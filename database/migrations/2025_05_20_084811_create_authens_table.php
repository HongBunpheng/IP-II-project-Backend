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
        Schema::create('authens', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the authenticator
            $table->string('email')->unique(); // Unique email for the authenticator
            $table->string('password'); // Password for the authenticator
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authens');
    }
};

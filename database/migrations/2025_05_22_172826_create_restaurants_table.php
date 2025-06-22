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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('promotion');
            $table->string('location');
            $table->string('address');
            $table->string('contact');
            $table->decimal('rating', 3, 2)->default(0);
            $table->decimal('price', 10, 2);
            $table->text('details');
            $table->json('image')->nullable();
            $table->json('detail_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};

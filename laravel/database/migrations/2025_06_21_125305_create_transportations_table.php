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
    Schema::create('transportations', function (Blueprint $table) {
        $table->id();
        $table->string('departure_time');
        $table->string('arrival_time');
        $table->string('departure_city');
        $table->string('arrival_city');
        $table->string('distance');
        $table->string('travel_time');
        $table->decimal('price', 8, 2);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transportations');
    }
};

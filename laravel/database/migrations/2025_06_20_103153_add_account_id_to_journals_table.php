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
    Schema::table('journals', function (Blueprint $table) {
        $table->unsignedBigInteger('account_id')->nullable(); // Allow NULL for now
    });
}
    public function down()
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropColumn('account_id');
        });
    }
};

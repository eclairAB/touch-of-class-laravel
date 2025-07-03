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
        Schema::table('commission_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('stylist_id')->nullable();
            $table->foreign('stylist_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commission_histories', function (Blueprint $table) {
            //
        });
    }
};

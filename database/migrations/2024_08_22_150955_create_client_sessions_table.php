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
        Schema::create('client_sessions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->mediumText('name');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->boolean('completed')->nullable()->default(false);

            $table->foreign('client_id')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_sessions');
    }
};

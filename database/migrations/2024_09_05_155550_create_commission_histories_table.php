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
        Schema::create('commission_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('session_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('package_redeem_id')->nullable();
            $table->unsignedBigInteger('combo_redeem_id')->nullable();
            $table->unsignedBigInteger('service_redeem_id')->nullable();
            $table->decimal('commission_amount', 8, 2)->nullable();

            $table->foreign('session_id')->references('id')->on('client_sessions');
            $table->foreign('service_id')->references('id')->on('clients');
            $table->foreign('package_redeem_id')->references('id')->on('appointment_package_redeems');
            $table->foreign('combo_redeem_id')->references('id')->on('appointment_combo_redeems');
            $table->foreign('service_redeem_id')->references('id')->on('appointment_service_redeems');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_histories');
    }
};

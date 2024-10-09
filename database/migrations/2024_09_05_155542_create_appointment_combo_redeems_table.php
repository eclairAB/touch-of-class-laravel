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
        Schema::create('appointment_combo_redeems', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('appointment_combo_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('cashier_id')->nullable();
            $table->unsignedBigInteger('stylist_id')->nullable();
            $table->smallInteger('service_no')->nullable()->default(1);
            $table->boolean('paid')->nullable()->default(false);

            $table->foreign('appointment_combo_id')->references('id')->on('appointment_combos')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('cashier_id')->references('id')->on('users');
            $table->foreign('stylist_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_combo_redeems');
    }
};

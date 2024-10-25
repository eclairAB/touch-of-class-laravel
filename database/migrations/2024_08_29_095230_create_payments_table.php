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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('appointment_package_id')->nullable();
            $table->unsignedBigInteger('appointment_combo_id')->nullable();
            $table->unsignedBigInteger('appointment_service_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('cashier_id')->nullable();
            $table->decimal('amount_paid', 8, 2)->nullable();
            $table->string('payment_milestone')->nullable(); // stores "downpayment", "follow-up", "final payment"
            $table->string('payment_method')->nullable();


            $table->foreign('appointment_package_id')->references('id')->on('appointment_packages')->onDelete('cascade');
            $table->foreign('appointment_combo_id')->references('id')->on('appointment_combos')->onDelete('cascade');
            $table->foreign('appointment_service_id')->references('id')->on('appointment_services')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('cashier_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

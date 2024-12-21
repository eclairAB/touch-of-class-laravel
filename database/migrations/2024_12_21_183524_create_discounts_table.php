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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('discount_name')->nullable();
            $table->boolean('percentage')->nullable()->default(false);
            $table->decimal('amount', 8, 2)->nullable();
            $table->unsignedBigInteger('appointment_package_id')->nullable();
            $table->unsignedBigInteger('appointment_combo_id')->nullable();
            $table->unsignedBigInteger('appointment_service_id')->nullable();

            $table->foreign('appointment_package_id')->references('id')->on('appointment_packages')->onDelete('cascade');
            $table->foreign('appointment_combo_id')->references('id')->on('appointment_combos')->onDelete('cascade');
            $table->foreign('appointment_service_id')->references('id')->on('appointment_services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};

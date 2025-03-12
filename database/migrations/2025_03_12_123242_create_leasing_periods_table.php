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
        Schema::create('leasing_periods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id');
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('leasing_construction_id');
            $table->unsignedInteger('leasing_construction_maximum_training')->nullable();
            $table->date('leasing_construction_maximumDate');
            $table->timestamp('leasing_actual_period_start_date')->nullable();
            $table->timestamp('leasing_next_check')->nullable();
            $table->boolean('is_leasing_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leasing_periods');
    }
};

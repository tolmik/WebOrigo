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
            $table->unsignedBigInteger('leasingConstructionId');
            $table->unsignedInteger('leasingConstructionMaximumTraining')->nullable();
            $table->date('leasingConstructionMaximumDate');
            $table->timestamp('leasingActualPeriodStartDate')->nullable();
            $table->timestamp('leasingNextCheck')->nullable();
            $table->boolean('isLeasingActive')->default(true);
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

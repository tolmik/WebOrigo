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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->unique('device_id_index');
            $table->enum('device_type', ['free', 'leasing']);
            $table->unsignedBigInteger('device_owner_id')->index('device_owner');
            $table->string('device_owner');
            $table->timestamp('dateof_registration');
            $table->timestamps();
            $table->softDeletes();

            /**
             * Add a special unique index, that contains the deviceId, and the deletedAt fields.
             * Since the table uses a soft delete we can add the same device multiple times,
             * but it can only ever be active as a single entry at any given time. This is in order
             * to keep track of the previous history of the device, while making them distinct from each other.
             */
            $table->unique(['deviceId', 'deletedAt'], 'deviceIdIndex');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};

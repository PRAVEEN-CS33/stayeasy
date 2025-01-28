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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accommodation_id');
            $table->unsignedTinyInteger('meals');
            $table->unsignedTinyInteger('power_backup');
            $table->unsignedTinyInteger('workout_zone');
            $table->unsignedTinyInteger('housekeeping');
            $table->unsignedTinyInteger('refrigerator');
            $table->unsignedTinyInteger('washing_machine');
            $table->unsignedTinyInteger('hot_water');
            $table->unsignedTinyInteger('water_purifier');
            $table->unsignedTinyInteger('television');
            $table->unsignedTinyInteger('biometric_entry');
            $table->timestamps();

            $table->foreign('accommodation_id')->references('accommodation_id')->on('accommodation_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

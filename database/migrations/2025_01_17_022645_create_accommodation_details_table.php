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
        Schema::create('accommodation_details', function (Blueprint $table) {
            $table->id('accommodation_id')->autoIncrement();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('accommodation_name');
            $table->string('accommodation_types');
            $table->string('description');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('pincode');
            $table->text('image')->nullable()->change();
            $table->string('gender_types');
            $table->string('preferred_by');
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('owners')->cascadeOnDelete()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodation_details');
    }
};

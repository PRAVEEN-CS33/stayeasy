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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('accommodation_id');
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('sharing_rent_type_id');
            $table->integer('no_of_slots');
            $table->date('check_in');
            $table->date('check_out');
            $table->string('amount');
            $table->string('status');
            $table->date('booking_date');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('accommodation_id')->references('accommodation_id')->on('accommodation_details');
            $table->foreign('owner_id')->references('id')->on('owners');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

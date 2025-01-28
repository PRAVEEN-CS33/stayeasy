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
        Schema::create('scheduled_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accommodation_id'); 
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('user_id');
            $table->date('visit_date');
            $table->string('status');
            $table->timestamps();

            $table->foreign('accommodation_id')->references('accommodation_id')->on('accommodation_details');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('owner_id')->references('id')->on('owners');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_visits');
    }
};

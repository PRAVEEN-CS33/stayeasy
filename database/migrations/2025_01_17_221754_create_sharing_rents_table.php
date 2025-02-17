<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Type\Integer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sharing_rents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accommodation_id');
            $table->string('sharing_type');
            $table->decimal('rent_amount', 10, 2);
            $table->integer("available_slots");
            $table->timestamps();

            $table->foreign('accommodation_id')->references('accommodation_id')->on('accommodation_details')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sharing_rents');
    }
};

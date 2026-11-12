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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id('room_id');
            $table->string('room_name');
            $table->string('room_type');
            $table->text('description');
            $table->integer('maximum_guests');
            $table->decimal('price_per_night', 10,2);
            $table->boolean('room_status')->default(true);
            $table->integer('contact_number');



            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};

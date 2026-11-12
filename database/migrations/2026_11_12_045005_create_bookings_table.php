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
            $table->id('booking_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms', 'room_id')->onDelete('cascade');
            $table->date('check_in');
            $table->date('check_out');
            $table->decimal('total_amount',10,2);
            $table->enum('booking_status',['Pending', 'Confirmed', 'Cancelled'])->default('Pending');
            $table->timestamp('booking_date')->useCurrent();
            $table->timestamps();
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

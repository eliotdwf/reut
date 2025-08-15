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
            $table->string('title')->comment('Title of the booking');
            $table->dateTime('starts_at')->comment('The start datetime of the booking');
            $table->dateTime('ends_at')->comment('The end datetime of the booking');
            $table->boolean('open_to_others')->default(false)->comment('Indicates if the person who booked the room accepts other people to join the room');
            $table->boolean('booking_perso')->default(false)->comment('Indicates if the booking is for an individual or for an association');
            $table->foreignId('room_id')
                ->constrained()
                ->cascadeOnDelete()
                ->comment('The room that is being booked');
            $table->foreignUuid('user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->comment('The user who made the booking');
            $table->foreignUuid('asso_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete()
                ->comment('The association for which the booking is being made, if not an individual booking');
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

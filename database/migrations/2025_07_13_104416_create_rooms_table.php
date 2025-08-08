<?php

use App\Enums\RoomType;
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
            $table->id();
            $table->string('name')->unique();
            $table->string('number')->unique();
            $table->text('description')->nullable();
            $table->enum('room_type',RoomType::values());
            $table->text('access_conditions')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('color')->default('#FF5733')->unique()->comment('Couleur de la salle utilisée pour l\'affichage du calendrier');
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

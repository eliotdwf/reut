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
        Schema::create('assos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('shortname');
            $table->string('login')->unique();
            $table->timestamps();
        });

        Schema::create('asso_members', function (Blueprint $table) {
            $table->foreignUuid('asso_id')->constrained();
            $table->foreignUuid('user_id')->constrained();
            $table->uuid('role_id');
            $table->primary(['asso_id', 'user_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the pivot table first to avoid foreign key constraint issues
        Schema::dropIfExists('asso_members');
        Schema::dropIfExists('assos',);
    }
};

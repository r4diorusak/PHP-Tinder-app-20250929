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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('liker_id')->constrained('people')->onDelete('cascade');
            $table->foreignId('liked_id')->constrained('people')->onDelete('cascade');
            $table->timestamps();

            // Ensure a person can only like another person once
            $table->unique(['liker_id', 'liked_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};

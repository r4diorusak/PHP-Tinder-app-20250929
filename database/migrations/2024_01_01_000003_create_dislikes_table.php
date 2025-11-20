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
        Schema::create('dislikes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disliker_id')->constrained('people')->onDelete('cascade');
            $table->foreignId('disliked_id')->constrained('people')->onDelete('cascade');
            $table->timestamps();

            // Ensure a person can only dislike another person once
            $table->unique(['disliker_id', 'disliked_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dislikes');
    }
};

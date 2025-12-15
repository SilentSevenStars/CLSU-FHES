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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->enum('question1', ['VS', 'S', 'F', 'P', 'NI']);
            $table->enum('question2', ['VS', 'S', 'F', 'P', 'NI']);
            $table->enum('question3', ['VS', 'S', 'F', 'P', 'NI']);
            $table->enum('question4', ['VS', 'S', 'F', 'P', 'NI']);
            $table->enum('question5', ['VS', 'S', 'F', 'P', 'NI']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};

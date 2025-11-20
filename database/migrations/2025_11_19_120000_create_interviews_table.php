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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->integer('general_appearance');
            $table->integer('manner_of_speaking');
            $table->integer('physical_conditions');
            $table->integer('alertness');
            $table->integer('self_confidence');
            $table->integer('ability_to_present_ideas');
            $table->integer('maturity_of_judgement');
            $table->integer('total_score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};

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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->integer('education_qualification');
            $table->integer('experience_type');
            $table->integer('licensure_examination');
            $table->integer('passing_licensure_examination');
            $table->integer('place_board_exam');
            $table->integer('professional_activities');
            $table->integer('academic_performance');
            $table->integer('publication');
            $table->integer('school_graduate');
            $table->integer('total_score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};

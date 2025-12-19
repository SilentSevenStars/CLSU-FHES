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
        Schema::create('position_ranks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // $table->integer('salary_grade');
            // $table->integer('point_bracket_minimum');
            // $table->integer('point_bracket_maximum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_ranks');
    }
};

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
        // give interview columns a default of 0 so partial inserts never fail
        Schema::table('interviews', function (Blueprint $table) {
            $table->integer('general_appearance')->default(0)->change();
            $table->integer('manner_of_speaking')->default(0)->change();
            $table->integer('physical_conditions')->default(0)->change();
            $table->integer('alertness')->default(0)->change();
            $table->integer('self_confidence')->default(0)->change();
            $table->integer('ability_to_present_ideas')->default(0)->change();
            $table->integer('maturity_of_judgement')->default(0)->change();
            $table->integer('total_score')->default(0)->change();
        });

        Schema::table('personal_competences', function (Blueprint $table) {
            $table->enum('question1', ['VS','S','F','P','NI'])->default('NI')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->integer('general_appearance')->default(null)->change();
            $table->integer('manner_of_speaking')->default(null)->change();
            $table->integer('physical_conditions')->default(null)->change();
            $table->integer('alertness')->default(null)->change();
            $table->integer('self_confidence')->default(null)->change();
            $table->integer('ability_to_present_ideas')->default(null)->change();
            $table->integer('maturity_of_judgement')->default(null)->change();
            $table->integer('total_score')->default(null)->change();
        });

        Schema::table('personal_competences', function (Blueprint $table) {
            $table->enum('question1', ['VS','S','F','P','NI'])->default(null)->change();
        });
    }
};
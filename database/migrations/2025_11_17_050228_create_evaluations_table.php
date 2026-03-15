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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->date('interview_date');
            $table->string('interview_room');
            $table->decimal('total_score', 8, 2)->nullable();
            $table->integer('rank')->nullable();
            $table->foreignId('job_application_id')->constrained()->cascadeOnDelete();
            $table->decimal('educational_score', 8, 2)->nullable();
            $table->decimal('experience_score', 8, 2)->nullable();
            $table->decimal('professional_dev_score', 8, 2)->nullable();
            $table->text('evaluator_remarks')->nullable();
            $table->text('verifier_remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};

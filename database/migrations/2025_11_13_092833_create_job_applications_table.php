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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->string('present_position');
            $table->string('education');
            $table->integer('experience');
            $table->string('training');
            $table->string('eligibility');
            $table->text('other_involvement');
            $table->string('requirements_file');
            $table->enum('status', ['decline', 'approve', 'hired', 'pending'])->default('pending');
            $table->date('interview_date')->nullable();
            $table->time('interview_time')->nullable();
            $table->string('interview_location')->nullable();
            $table->foreignId('position_id')->constrained()->cascadeOnDelete();
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade');
            $table->boolean('archive')->default(false);
            $table->boolean('hired')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};

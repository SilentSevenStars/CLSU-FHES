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
            $table->longText('present_position')->nullable();
            $table->longText('education');
            $table->integer('experience');
            $table->integer('training');
            $table->longText('eligibility');
            $table->longText('other_involvement')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->longText('requirements_file');
            $table->enum('status', ['decline', 'approve', 'hired', 'pending'])->default('pending');
            $table->foreignId('position_id')->constrained()->cascadeOnDelete();
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade');
            $table->boolean('archive')->default(false);
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

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
        Schema::create('nbc_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nbc_committee_id')->constrained('nbc_committees')->onDelete('cascade');
            $table->foreignId('evaluation_id')->constrained('evaluations')->onDelete('cascade');
            $table->foreignId('educational_qualification_id')->nullable()->constrained('educational_qualifications')->onDelete('cascade');
            $table->foreignId('experience_service_id')->nullable()->constrained('experience_services')->onDelete('cascade');
            $table->foreignId('professional_development_id')->nullable()->constrained('professional_developments')->onDelete('cascade');
            $table->enum('status', ['pending', 'complete'])->default('pending');
            $table->enum('type', ['evaluate', 'verify'])->default('evaluate');
            $table->timestamps();

            $table->index(['nbc_committee_id', 'status']);
            $table->index(['evaluation_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nbc_assignments');
    }
};

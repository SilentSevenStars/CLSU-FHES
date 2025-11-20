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
        Schema::create('panel_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained()->onDelete('cascade');
            $table->foreignId('interview_id')->constrained()->onDelete('cascade');
            $table->foreignId('experience_id')->constrained()->onDelete('cascade');
            $table->foreignId('performance_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['not yet', 'complete'])->default('not yet');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panel_assignments');
    }
};

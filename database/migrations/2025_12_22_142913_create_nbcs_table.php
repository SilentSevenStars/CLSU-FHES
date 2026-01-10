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
        Schema::create('nbcs', function (Blueprint $table) {
            $table->id();
            $table->decimal('educational_qualification', 5, 3);
            $table->decimal('experience', 5, 3);            
            $table->decimal('professional_development', 5,3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nbcs');
    }
};

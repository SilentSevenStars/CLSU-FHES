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
        Schema::create('experience_services', function (Blueprint $table) {
            $table->id();
            $table->decimal('subtotal', 8, 2)->default(0);
            $table->decimal('rs_2_1_1', 8, 2)->nullable();
            $table->decimal('ep_2_1_1', 8, 2)->nullable();
            $table->decimal('rs_2_1_2', 8, 2)->nullable();
            $table->decimal('ep_2_1_2', 8, 2)->nullable();
            
            $table->decimal('rs_2_2_1', 8, 2)->nullable();
            $table->decimal('ep_2_2_1', 8, 2)->nullable();

            $table->decimal('rs_2_3_1', 8, 2)->nullable();
            $table->decimal('ep_2_3_1', 8, 2)->nullable();

            $table->decimal('rs_2_3_2', 8, 2)->nullable();
            $table->decimal('ep_2_3_2', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experience_services');
    }
};

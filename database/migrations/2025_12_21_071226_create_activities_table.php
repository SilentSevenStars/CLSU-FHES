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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->decimal('subtotal', 8, 3)->default(0);
            $table->decimal('q3_2_1_1_a', 8, 3);
            $table->decimal('q3_2_1_1_b', 8, 3);
            $table->decimal('q3_2_1_1_c', 8, 3);
            $table->decimal('q3_2_1_2', 8, 3);
            $table->decimal('q3_2_1_3_a', 8, 3);
            $table->decimal('q3_2_1_3_b', 8, 3);
            $table->decimal('q3_2_1_3_c', 8, 3);
            $table->decimal('q3_2_2_1_a', 8, 3);
            $table->decimal('q3_2_2_1_b', 8, 3);
            $table->decimal('q3_2_2_1_c', 8, 3);
            $table->decimal('q3_2_2_2', 8, 3);
            $table->decimal('q3_2_2_3', 8, 3);
            $table->decimal('q3_2_2_4', 8, 3);
            $table->decimal('q3_2_2_5', 8, 3);
            $table->decimal('q3_2_2_6', 8, 3);
            $table->decimal('q3_2_2_7', 8, 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};

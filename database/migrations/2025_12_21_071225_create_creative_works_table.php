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
        Schema::create('creative_works', function (Blueprint $table) {
            $table->id();
            $table->decimal('subtotal', 8, 3)->default(0);
            $table->decimal('q3_1_1', 8, 3);
            $table->decimal('q3_1_2_a', 8, 3);
            $table->decimal('q3_1_2_c', 8, 3);
            $table->decimal('q3_1_2_d', 8, 3);
            $table->decimal('q3_1_2_e', 8, 3);
            $table->decimal('q3_1_2_f', 8, 3);
            $table->decimal('q3_1_3_a', 8, 3);
            $table->decimal('q3_1_3_b', 8, 3);
            $table->decimal('q3_1_3_c', 8, 3);
            $table->decimal('q3_1_4', 8, 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creative_works');
    }
};

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
        Schema::create('licensures', function (Blueprint $table) {
            $table->id();
            $table->decimal('subtotal', 8, 3);
            $table->decimal('q3_6_1_a', 8, 3);
            $table->decimal('q3_6_1_b', 8, 3);
            $table->decimal('q3_6_1_c', 8, 3);
            $table->decimal('q3_6_1_d', 8, 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licensures');
    }
};

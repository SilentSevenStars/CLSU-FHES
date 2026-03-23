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
        Schema::create('recognitions', function (Blueprint $table) {
            $table->id();
            $table->decimal('subtotal', 8, 3)->default(0);
            $table->decimal('q3_3_1_a_full_member', 8, 3);
            $table->decimal('q3_3_1_a_associate_member', 8, 3);
            $table->decimal('q3_3_1_b', 8, 3);
            $table->decimal('q3_3_1_c', 8, 3);
            $table->decimal('q3_3_1_d_officer', 8, 3);
            $table->decimal('q3_3_1_d_member', 8, 3);
            $table->decimal('q3_3_2_a', 8, 3);
            $table->decimal('q3_3_2_b', 8, 3);
            $table->decimal('q3_3_2_c', 8, 3);
            $table->decimal('q3_3_3_a_doctorate', 8, 3);
            $table->decimal('q3_3_3_a_masters', 8, 3);
            $table->decimal('q3_3_3_a_nondegree', 8, 3);
            $table->decimal('q3_3_3_b_doctorate', 8, 3);
            $table->decimal('q3_3_3_b_masters', 8, 3);
            $table->decimal('q3_3_3_b_nondegree', 8, 3);
            $table->decimal('q3_3_3_c_doctorate', 8, 3);
            $table->decimal('q3_3_3_c_masters', 8, 3);
            $table->decimal('q3_3_3_c_nondegree', 8, 3);
            $table->decimal('q3_3_3_d_doctorate', 8, 3);
            $table->decimal('q3_3_3_d_masters', 8, 3);
            $table->decimal('q3_3_3_e', 8, 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recognitions');
    }
};

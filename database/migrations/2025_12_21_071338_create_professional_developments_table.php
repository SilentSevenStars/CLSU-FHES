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
        Schema::create('professional_developments', function (Blueprint $table) {
            $table->id();
            $table->decimal('rs_3_1_1', 8, 3)->nullable();
            $table->decimal('rs_3_1_2_a', 8, 3)->nullable();
            $table->decimal('rs_3_1_2_c', 8, 3)->nullable();
            $table->decimal('rs_3_1_2_d', 8, 3)->nullable();
            $table->decimal('rs_3_1_2_e', 8, 3)->nullable();
            $table->decimal('rs_3_1_2_f', 8, 3)->nullable();
            $table->decimal('rs_3_1_3_a', 8, 3)->nullable();
            $table->decimal('rs_3_1_3_b', 8, 3)->nullable();
            $table->decimal('rs_3_1_3_c', 8, 3)->nullable();
            $table->decimal('rs_3_1_4', 8, 3)->nullable();

            /* =======================
             * SECTION 3.2.1 (MAX 10)
             * ======================= */
            $table->decimal('rs_3_2_1_1_a', 8, 3)->nullable();
            $table->decimal('rs_3_2_1_1_b', 8, 3)->nullable();
            $table->decimal('rs_3_2_1_1_c', 8, 3)->nullable();
            $table->decimal('rs_3_2_1_2', 8, 3)->nullable();
            $table->decimal('rs_3_2_1_3_a', 8, 3)->nullable();
            $table->decimal('rs_3_2_1_3_b', 8, 3)->nullable();
            $table->decimal('rs_3_2_1_3_c', 8, 3)->nullable();

            /* =======================
             * SECTION 3.2.2 (MAX 20)
             * ======================= */
            $table->decimal('rs_3_2_2_1_a', 8, 3)->nullable();
            $table->decimal('rs_3_2_2_1_b', 8, 3)->nullable();
            $table->decimal('rs_3_2_2_1_c', 8, 3)->nullable();
            $table->decimal('rs_3_2_2_2', 8, 3)->nullable();
            $table->decimal('rs_3_2_2_3', 8, 3)->nullable();
            $table->decimal('rs_3_2_2_4', 8, 3)->nullable();
            $table->decimal('rs_3_2_2_5', 8, 3)->nullable();
            $table->decimal('rs_3_2_2_6', 8, 3)->nullable();
            $table->decimal('rs_3_2_2_7', 8, 3)->nullable();

            /* =======================
             * SECTION 3.3 (MAX 30)
             * ======================= */
            $table->decimal('rs_3_3_1_a', 8, 3)->nullable();
            $table->decimal('rs_3_3_1_b', 8, 3)->nullable();
            $table->decimal('rs_3_3_1_c', 8, 3)->nullable();
            $table->decimal('rs_3_3_2', 8, 3)->nullable();

            $table->decimal('rs_3_3_3_a_doctorate', 8, 3)->nullable();
            $table->decimal('rs_3_3_3_a_masters', 8, 3)->nullable();
            $table->decimal('rs_3_3_3_a_nondegree', 8, 3)->nullable();

            $table->decimal('rs_3_3_3_b_doctorate', 8, 3)->nullable();
            $table->decimal('rs_3_3_3_b_masters', 8, 3)->nullable();
            $table->decimal('rs_3_3_3_b_nondegree', 8, 3)->nullable();

            $table->decimal('rs_3_3_3_c_doctorate', 8, 3)->nullable();
            $table->decimal('rs_3_3_3_c_masters', 8, 3)->nullable();
            $table->decimal('rs_3_3_3_c_nondegree', 8, 3)->nullable();

            $table->decimal('rs_3_3_3_d_doctorate', 8, 3)->nullable();
            $table->decimal('rs_3_3_3_d_masters', 8, 3)->nullable();
            $table->decimal('rs_3_3_3_e', 8, 3)->nullable();

            $table->decimal('rs_3_4_a', 8, 3)->default(0);
            $table->decimal('rs_3_4_b', 8, 3)->default(0);
            $table->decimal('rs_3_4_c', 8, 3)->default(0);

            /* =======================
             * SECTION 3.5 (MAX 5)
             * ======================= */
            $table->decimal('rs_3_5_1', 8, 3)->nullable();

            /* =======================
             * SECTION 3.6 (MAX 10)
             * ======================= */
            $table->decimal('rs_3_6_1_a', 8, 3)->nullable();
            $table->decimal('rs_3_6_1_b', 8, 3)->nullable();
            $table->decimal('rs_3_6_1_c', 8, 3)->nullable();
            $table->decimal('rs_3_6_1_d', 8, 3)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professional_developments');
    }
};

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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->text('first_name');
            $table->text('middle_name')->nullable();
            $table->text('last_name');
            $table->text('suffix')->nullable();
            $table->text('phone_number')->nullable();
            $table->text('region')->nullable();
            $table->text('province')->nullable();
            $table->text('city')->nullable();
            $table->text('barangay')->nullable();
            $table->text('street')->nullable();
            $table->text('postal_code')->nullable();
            $table->string('position')->nullable();
            $table->boolean('hired')->default(false);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('college_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};

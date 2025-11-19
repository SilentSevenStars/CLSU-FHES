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
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('requirements_file')->nullable()->after('other_involvement');
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade')->after('requirements_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropForeign(['applicant_id']);
            $table->dropColumn(['requirements_file', 'applicant_id']);
        });
    }
};

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
            if (!Schema::hasColumn('job_applications', 'created_at')) {
                $table->timestamps();
            }
            if (!Schema::hasColumn('job_applications', 'interview_date')) {
                $table->date('interview_date')->nullable()->after('status');
            }
            if (!Schema::hasColumn('job_applications', 'interview_time')) {
                $table->time('interview_time')->nullable()->after('interview_date');
            }
            if (!Schema::hasColumn('job_applications', 'interview_location')) {
                $table->string('interview_location')->nullable()->after('interview_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at', 'interview_date', 'interview_time', 'interview_location']);
        });
    }
};

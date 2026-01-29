<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Legacy DBs in this project may have `departments.college` (string) instead of `college_id` (FK).
        // The app code expects `college_id`, so we add it and backfill when possible.
        if (!Schema::hasColumn('departments', 'college_id')) {
            Schema::table('departments', function (Blueprint $table) {
                $table->foreignId('college_id')->nullable()->after('name')->index();
            });
        }

        // Backfill by matching `departments.college` (name) -> `colleges.name`
        if (Schema::hasColumn('departments', 'college') && Schema::hasColumn('departments', 'college_id')) {
            DB::statement("
                UPDATE departments d
                JOIN colleges c ON c.name = d.college
                SET d.college_id = c.id
                WHERE d.college_id IS NULL
            ");
        }

        // Add FK constraint if not already present.
        // MySQL constraint name can vary; we use the default Laravel naming.
        try {
            Schema::table('departments', function (Blueprint $table) {
                $table->foreign('college_id')->references('id')->on('colleges')->onDelete('cascade');
            });
        } catch (\Throwable $e) {
            // Ignore if constraint already exists or cannot be created (e.g., bad existing data).
        }

        // If every row is backfilled, make it NOT NULL (keeps admin UI validation consistent).
        if (Schema::hasColumn('departments', 'college_id')) {
            $nullCount = DB::table('departments')->whereNull('college_id')->count();
            if ($nullCount === 0) {
                Schema::table('departments', function (Blueprint $table) {
                    $table->foreignId('college_id')->nullable(false)->change();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('departments', 'college_id')) {
            // Drop FK first if it exists.
            try {
                Schema::table('departments', function (Blueprint $table) {
                    $table->dropForeign(['college_id']);
                });
            } catch (\Throwable $e) {
                // ignore
            }

            Schema::table('departments', function (Blueprint $table) {
                $table->dropColumn('college_id');
            });
        }
    }
};

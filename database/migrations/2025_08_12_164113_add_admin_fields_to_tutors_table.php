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
        Schema::table('tutors', function (Blueprint $table) {
            if (!Schema::hasColumn('tutors', 'status_reason')) {
                $table->text('status_reason')->nullable()->after('status');
            }
            if (!Schema::hasColumn('tutors', 'status_updated_at')) {
                $table->timestamp('status_updated_at')->nullable()->after('status_reason');
            }
            if (!Schema::hasColumn('tutors', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('status_updated_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutors', function (Blueprint $table) {
            $table->dropColumn(['status_reason', 'status_updated_at', 'approved_at']);
        });
    }
};

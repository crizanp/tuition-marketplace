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
        Schema::table('tutor_jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('tutor_jobs', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status');
            }
            if (!Schema::hasColumn('tutor_jobs', 'featured_at')) {
                $table->timestamp('featured_at')->nullable()->after('is_featured');
            }
            if (!Schema::hasColumn('tutor_jobs', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('featured_at');
            }
            if (!Schema::hasColumn('tutor_jobs', 'admin_updated_at')) {
                $table->timestamp('admin_updated_at')->nullable()->after('admin_notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_jobs', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'featured_at', 'admin_notes', 'admin_updated_at']);
        });
    }
};

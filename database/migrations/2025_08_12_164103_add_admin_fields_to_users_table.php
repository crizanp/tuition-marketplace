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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'status_reason')) {
                $table->text('status_reason')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'status_updated_at')) {
                $table->timestamp('status_updated_at')->nullable()->after('status_reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'status_reason', 'status_updated_at']);
        });
    }
};
